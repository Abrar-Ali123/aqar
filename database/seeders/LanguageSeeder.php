<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            [
                'code' => 'ar',
                'name' => 'العربية',
                'direction' => 'rtl',
                'is_default' => true,
                'is_active' => true
            ],
            [
                'code' => 'en',
                'name' => 'English',
                'direction' => 'ltr',
                'is_default' => false,
                'is_active' => true
            ],
            [
                'code' => 'fr',
                'name' => 'Français',
                'direction' => 'ltr',
                'is_default' => false,
                'is_active' => true
            ],
            [
                'code' => 'es',
                'name' => 'Español',
                'direction' => 'ltr',
                'is_default' => false,
                'is_active' => true
            ],
            [
                'code' => 'de',
                'name' => 'Deutsch',
                'direction' => 'ltr',
                'is_default' => false,
                'is_active' => true
            ],
            [
                'code' => 'tr',
                'name' => 'Türkçe',
                'direction' => 'ltr',
                'is_default' => false,
                'is_active' => true
            ]
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}
