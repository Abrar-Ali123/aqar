<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * معالجة وحفظ صورة
     *
     * @param UploadedFile $image الصورة المرفوعة
     * @param string $path مسار حفظ الصورة
     * @param array $sizes أحجام الصور المطلوبة
     * @return array مصفوفة تحتوي على مسارات الصور المعالجة
     */
    public function processAndSave(UploadedFile $image, string $path, array $sizes = []): array
    {
        $paths = [];
        $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
        $fullPath = $path . '/' . $filename;

        // حفظ الصورة الأصلية
        $paths['original'] = $fullPath;
        Storage::disk('public')->put($fullPath, file_get_contents($image));

        // معالجة الأحجام المطلوبة
        foreach ($sizes as $size => $dimensions) {
            $resizedImage = Image::make($image)
                ->fit($dimensions['width'], $dimensions['height'])
                ->encode();

            $sizePath = $path . '/' . $size . '_' . $filename;
            Storage::disk('public')->put($sizePath, $resizedImage);
            $paths[$size] = $sizePath;
        }

        return $paths;
    }

    /**
     * حذف صورة وجميع نسخها
     *
     * @param string $path مسار الصورة الأصلية
     * @param array $sizes الأحجام المخزنة
     * @return bool
     */
    public function deleteImage(string $path, array $sizes = []): bool
    {
        try {
            // حذف الصورة الأصلية
            Storage::disk('public')->delete($path);

            // حذف النسخ المعدلة
            $directory = dirname($path);
            $filename = basename($path);
            foreach ($sizes as $size => $_) {
                Storage::disk('public')->delete($directory . '/' . $size . '_' . $filename);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('خطأ في حذف الصورة: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * تحديث ترتيب الصور
     *
     * @param array $images مصفوفة الصور مع الترتيب الجديد
     * @return bool
     */
    public function updateImagesOrder(array $images): bool
    {
        try {
            foreach ($images as $order => $imageId) {
                DB::table('facility_images')
                    ->where('id', $imageId)
                    ->update(['order' => $order]);
            }
            return true;
        } catch (\Exception $e) {
            \Log::error('خطأ في تحديث ترتيب الصور: ' . $e->getMessage());
            return false;
        }
    }
}
