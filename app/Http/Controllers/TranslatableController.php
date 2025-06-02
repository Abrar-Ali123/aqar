<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

abstract class TranslatableController extends Controller
{
    protected $translatableFields = [];
    
    protected function getLanguages()
    {
        return Language::active()->orderBy('sort_order')->get();
    }

    protected function handleTranslations($model, Request $request, array $fields)
    {
        foreach ($fields as $field) {
            $translations = [];
            foreach ($this->getLanguages() as $language) {
                $key = "{$field}_{$language->code}";
                if ($request->has($key)) {
                    $translations[$language->code] = $request->input($key);
                }
            }
            $model->setTranslations($field, $translations);
        }
        return $model;
    }

    protected function prepareTranslations($model, array $fields)
    {
        $translations = [];
        foreach ($fields as $field) {
            foreach ($this->getLanguages() as $language) {
                $key = "{$field}_{$language->code}";
                $translations[$key] = $model->translate($field, $language->code);
            }
        }
        return $translations;
    }
}
