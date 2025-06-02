<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class TranslatableFieldCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_category_with_translations()
    {
        $response = $this->post('/dashboard/categories', [
            'translations' => [
                'ar' => ['name' => 'فئة تجريبية'],
                'en' => ['name' => 'Test Category'],
            ],
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('categories', []); // تحقق من وجود سجل
        $this->assertDatabaseHas('category_translations', [
            'locale' => 'ar',
            'name' => 'فئة تجريبية',
        ]);
        $this->assertDatabaseHas('category_translations', [
            'locale' => 'en',
            'name' => 'Test Category',
        ]);
    }
}
