<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageTemplate;

class PageTemplateSeeder extends Seeder
{
    public function run()
    {
        PageTemplate::create([
            'name' => 'عن المنشأة',
            'slug' => 'about',
            'default_attributes' => json_encode([
                ['key' => 'title', 'type' => 'text', 'label' => 'العنوان'],
                ['key' => 'description', 'type' => 'wysiwyg', 'label' => 'الوصف'],
                ['key' => 'cover_image', 'type' => 'image', 'label' => 'صورة الغلاف'],
            ]),
            'preview_image' => null,
        ]);
        PageTemplate::create([
            'name' => 'معرض الصور',
            'slug' => 'gallery',
            'default_attributes' => json_encode([
                ['key' => 'gallery', 'type' => 'gallery', 'label' => 'الصور'],
            ]),
            'preview_image' => null,
        ]);
        PageTemplate::create([
            'name' => 'فريق العمل',
            'slug' => 'team',
            'default_attributes' => json_encode([
                ['key' => 'members', 'type' => 'repeater', 'label' => 'الأعضاء', 'fields' => [
                    ['key' => 'name', 'type' => 'text', 'label' => 'الاسم'],
                    ['key' => 'role', 'type' => 'text', 'label' => 'الوظيفة'],
                    ['key' => 'image', 'type' => 'image', 'label' => 'الصورة'],
                ]]
            ]),
            'preview_image' => null,
        ]);
        PageTemplate::create([
            'name' => 'خدمات',
            'slug' => 'services',
            'default_attributes' => json_encode([
                ['key' => 'services', 'type' => 'repeater', 'label' => 'الخدمات', 'fields' => [
                    ['key' => 'title', 'type' => 'text', 'label' => 'اسم الخدمة'],
                    ['key' => 'desc', 'type' => 'textarea', 'label' => 'وصف الخدمة'],
                    ['key' => 'icon', 'type' => 'icon', 'label' => 'أيقونة'],
                ]]
            ]),
            'preview_image' => null,
        ]);
    }
}
