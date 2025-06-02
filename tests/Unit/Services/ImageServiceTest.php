<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageServiceTest extends TestCase
{
    protected ImageService $imageService;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->imageService = app(ImageService::class);
    }

    /**
     * اختبار حفظ الصورة
     *
     * @return void
     */
    public function test_can_save_image()
    {
        $file = UploadedFile::fake()->image('test.jpg');
        
        $path = $this->imageService->save($file, 'facilities');
        
        Storage::disk('public')->assertExists($path);
    }

    /**
     * اختبار حذف الصورة
     *
     * @return void
     */
    public function test_can_delete_image()
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $path = $this->imageService->save($file, 'facilities');
        
        $this->imageService->delete($path);
        
        Storage::disk('public')->assertMissing($path);
    }

    /**
     * اختبار تغيير حجم الصورة
     *
     * @return void
     */
    public function test_can_resize_image()
    {
        $file = UploadedFile::fake()->image('test.jpg', 1000, 1000);
        
        $path = $this->imageService->saveWithSize($file, 'facilities', 500, 500);
        
        $this->assertFileExists(Storage::disk('public')->path($path));
        
        // تحقق من أبعاد الصورة
        list($width, $height) = getimagesize(Storage::disk('public')->path($path));
        $this->assertEquals(500, $width);
        $this->assertEquals(500, $height);
    }
}
