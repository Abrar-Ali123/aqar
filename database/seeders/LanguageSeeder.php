<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'name' => 'العربية',
                'code' => 'ar',
                'direction' => 'rtl',
                'is_active' => true,
                'is_default' => true,
                'is_required' => true,
                'order' => 1,
            ],
            [
                'name' => 'English',
                'code' => 'en',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'is_required' => true,
                'order' => 2,
            ],
            [
                'name' => 'Français',
                'code' => 'fr',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'is_required' => false,
                'order' => 3,
            ],
            [
                'name' => 'Español',
                'code' => 'es',
                'direction' => 'ltr',
                'is_active' => true,
                'is_default' => false,
                'is_required' => false,
                'order' => 4,
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
