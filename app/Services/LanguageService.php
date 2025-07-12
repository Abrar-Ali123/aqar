<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LanguageService
{
    /**
     * Create a new language.
     *
     * @param array $data
     * @return Language
     * @throws \Exception
     */
    public function createLanguage(array $data): Language
    {
        return DB::transaction(function () use ($data) {
            $data['is_default'] = isset($data['is_default']);
            $data['is_required'] = isset($data['is_required']);

            $language = Language::create($data);

            if (isset($data['flag'])) {
                $path = $data['flag']->store('languages/flags', 'public');
                $language->update(['flag' => $path]);
            }

            if (isset($data['create_translation_file'])) {
                $this->createTranslationFile($language->code);
            }

            if ($language->is_default) {
                Language::where('id', '!=', $language->id)->update(['is_default' => false]);
            }

            return $language;
        });
    }

    /**
     * Update an existing language.
     *
     * @param Language $language
     * @param array $data
     * @return Language
     * @throws \Exception
     */
    public function updateLanguage(Language $language, array $data): Language
    {
        return DB::transaction(function () use ($language, $data) {
            $data['is_default'] = isset($data['is_default']);
            $data['is_required'] = isset($data['is_required']);

            if (isset($data['flag'])) {
                if ($language->flag) {
                    Storage::disk('public')->delete($language->flag);
                }
                $path = $data['flag']->store('languages/flags', 'public');
                $data['flag'] = $path;
            }

            $language->update($data);

            if ($language->is_default) {
                Language::where('id', '!=', $language->id)->update(['is_default' => false]);
            }

            return $language;
        });
    }

    /**
     * Delete a language.
     *
     * @param Language $language
     * @return void
     * @throws \Exception
     */
    public function deleteLanguage(Language $language): void
    {
        if ($language->is_required) {
            throw new \Exception(__('messages.cannot_delete_required_language'));
        }

        if ($this->hasTranslations($language)) {
            throw new \Exception(__('messages.language_has_translations'));
        }

        DB::transaction(function () use ($language) {
            if ($language->flag) {
                Storage::disk('public')->delete($language->flag);
            }

            $this->deleteTranslationFile($language->code);

            $isDefault = $language->is_default;
            $language->delete();

            if ($isDefault) {
                $firstLanguage = Language::first();
                if ($firstLanguage) {
                    $firstLanguage->update(['is_default' => true]);
                }
            }
        });
    }

    /**
     * Check if a language has any translations in the system.
     *
     * @param Language $language
     * @return bool
     */
    private function hasTranslations(Language $language): bool
    {
        $translationTables = [
            'attribute_translations',
            'category_translations',
            'feature_translations',
            'product_translations',
            // Add other translation tables here as the system grows
        ];

        foreach ($translationTables as $table) {
            if (DB::table($table)->where('locale', $language->code)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a translation file for a given language code.
     *
     * @param string $code
     * @return void
     */
    private function createTranslationFile(string $code): void
    {
        $path = lang_path("{$code}.json");

        if (!File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        if (!File::exists($path)) {
            File::put($path, '{}');
        }
    }

    /**
     * Delete the translation file for a given language code.
     *
     * @param string $code
     * @return void
     */
    private function deleteTranslationFile(string $code): void
    {
        $path = lang_path("{$code}.json");
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
