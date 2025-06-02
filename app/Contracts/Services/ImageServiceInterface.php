<?php

namespace App\Contracts\Services;

use Illuminate\Http\UploadedFile;

interface ImageServiceInterface
{
    /**
     * معالجة وحفظ صورة
     *
     * @param UploadedFile $image
     * @param string $path
     * @param array $sizes
     * @return array
     */
    public function processAndSave(UploadedFile $image, string $path, array $sizes = []): array;

    /**
     * حذف صورة وجميع نسخها
     *
     * @param string $path
     * @param array $sizes
     * @return bool
     */
    public function deleteImage(string $path, array $sizes = []): bool;

    /**
     * تحديث ترتيب الصور
     *
     * @param array $images
     * @return bool
     */
    public function updateImagesOrder(array $images): bool;
}
