<?php

namespace Database\Seeders;

use App\Models\PageTemplate;
use Illuminate\Database\Seeder;

class PageTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Basic Template',
                'slug' => 'basic',
                'description' => 'Basic facility template',
                'layout' => 'default',
                'is_active' => true,
                'settings' => [
                    'allowed_components' => ['text', 'image', 'gallery', 'contact', 'map'],
                    'max_sections' => 10
                ]
            ],
            [
                'name' => 'Store Template',
                'slug' => 'store',
                'description' => 'Store specific template',
                'layout' => 'store',
                'is_active' => true,
                'settings' => [
                    'allowed_components' => ['text', 'image', 'gallery', 'products', 'contact', 'map'],
                    'max_sections' => 12
                ]
            ]
        ];

        foreach ($templates as $template) {
            PageTemplate::create($template);
        }
    }
}
