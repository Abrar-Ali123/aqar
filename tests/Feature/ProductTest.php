<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Facility;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_product_with_translations_and_image()
    {
        $this->withoutExceptionHandling();

        // إعداد بيانات أساسية
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $facility = Facility::factory()->create();

        $payload = [
            'type' => 'physical',
            'price' => 100,
            'category_id' => $category->id,
            'facility_id' => $facility->id,
            'translations' => [
                'ar' => ['name' => 'منتج اختبار', 'description' => 'وصف بالعربية'],
                'en' => ['name' => 'Test Product', 'description' => 'Description in English'],
            ],
            'images' => [UploadedFile::fake()->image('product.jpg')],
        ];

        $response = $this->actingAs($user)
            ->post(route('products.store'), $payload);

        $response->assertStatus(302); // إعادة توجيه بعد النجاح
        $this->assertDatabaseHas('products', [
            'category_id' => $category->id,
            'facility_id' => $facility->id,
        ]);
        $this->assertDatabaseHas('product_translations', [
            'name' => 'منتج اختبار',
            'locale' => 'ar',
        ]);
        $this->assertDatabaseHas('product_translations', [
            'name' => 'Test Product',
            'locale' => 'en',
        ]);
    }
}
