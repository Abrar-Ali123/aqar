<?php

namespace App\Jobs;

use App\Models\Facility;
use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFacilityImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $facility;
    protected $images;

    /**
     * إنشاء مهمة جديدة
     *
     * @param Facility $facility
     * @param array $images
     */
    public function __construct(Facility $facility, array $images)
    {
        $this->facility = $facility;
        $this->images = $images;
    }

    /**
     * تنفيذ المهمة
     *
     * @param ImageService $imageService
     * @return void
     */
    public function handle(ImageService $imageService)
    {
        foreach ($this->images as $image) {
            $sizes = [
                'thumbnail' => ['width' => 150, 'height' => 150],
                'medium' => ['width' => 300, 'height' => 300],
                'large' => ['width' => 800, 'height' => 600]
            ];

            $paths = $imageService->processAndSave($image, "facilities/{$this->facility->id}", $sizes);

            $this->facility->images()->create([
                'path' => $paths['original'],
                'thumbnail' => $paths['thumbnail'],
                'medium' => $paths['medium'],
                'large' => $paths['large'],
                'order' => $this->facility->images()->count()
            ]);
        }
    }
}
