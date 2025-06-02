<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ProductType;

class TranslatableFieldTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_product_type_with_translations()
    {
        $response = $this->post('/product-types', [
            'key' => 'test_key',
            'translations' => [
                'ar' => ['label' => 'منتج تجريبي'],
                'en' => ['label' => 'Test Product'],
            ],
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('product_types', ['key' => 'test_key']);
        $this->assertDatabaseHas('product_type_translations', [
            'label' => 'منتج تجريبي',
            'locale' => 'ar',
        ]);
        $this->assertDatabaseHas('product_type_translations', [
            'label' => 'Test Product',
            'locale' => 'en',
        ]);
    }
}
