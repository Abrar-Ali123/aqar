<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class MediaManager extends Component
{
    use WithFileUploads;

    public $model;
    public $collection = 'default';
    public $files = [];
    public $uploadedFiles = [];
    public $maxFileSize = 5120; // 5MB
    public $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    public $maxFiles = 10;
    public $imageQuality = 80;
    public $generateThumbnails = true;
    public $thumbnailSizes = [
        'thumb' => [150, 150],
        'medium' => [300, 300],
        'large' => [600, 600]
    ];

    protected $listeners = [
        'mediaUploaded' => 'handleUploadComplete',
        'mediaDeleted' => 'handleDelete'
    ];

    public function mount($model, $collection = 'default')
    {
        $this->model = $model;
        $this->collection = $collection;
        $this->loadExistingMedia();
    }

    public function loadExistingMedia()
    {
        $this->files = $this->model
            ->media()
            ->where('collection_name', $this->collection)
            ->orderBy('order')
            ->get()
            ->map(function($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->name,
                    'url' => $media->getUrl(),
                    'thumbnail' => $media->getUrl('thumb'),
                    'size' => $media->size,
                    'order' => $media->order,
                    'meta' => $media->meta
                ];
            })
            ->toArray();
    }

    public function updatedUploadedFiles()
    {
        $this->validate([
            'uploadedFiles.*' => "required|file|max:{$this->maxFileSize}|mimes:jpeg,png,gif"
        ]);

        foreach ($this->uploadedFiles as $file) {
            $this->processUpload($file);
        }

        $this->uploadedFiles = [];
        $this->loadExistingMedia();
        $this->emit('mediaUploaded');
    }

    protected function processUpload($file)
    {
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("media/{$this->collection}", $filename, 'public');

        if ($this->generateThumbnails && in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            $this->generateThumbnails($path, $filename);
        }

        $this->model->media()->create([
            'name' => $file->getClientOriginalName(),
            'file_name' => $filename,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'collection_name' => $this->collection,
            'order' => count($this->files) + 1,
            'meta' => [
                'alt' => '',
                'title' => '',
                'description' => ''
            ]
        ]);
    }

    protected function generateThumbnails($path, $filename)
    {
        $image = Image::make(Storage::disk('public')->path($path));

        foreach ($this->thumbnailSizes as $size => [$width, $height]) {
            $image->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumbPath = "media/{$this->collection}/thumbnails/{$size}_{$filename}";
            Storage::disk('public')->put(
                $thumbPath,
                $image->encode(null, $this->imageQuality)
            );
        }
    }

    public function updateOrder($orderedIds)
    {
        foreach ($orderedIds as $order => $id) {
            $this->model->media()
                ->where('id', $id)
                ->update(['order' => $order + 1]);
        }

        $this->loadExistingMedia();
        $this->emit('mediaReordered');
    }

    public function deleteMedia($id)
    {
        $media = $this->model->media()->findOrFail($id);
        
        // حذف الملف الأصلي
        Storage::disk('public')->delete("media/{$this->collection}/{$media->file_name}");
        
        // حذف المصغرات
        foreach ($this->thumbnailSizes as $size => $dimensions) {
            Storage::disk('public')->delete(
                "media/{$this->collection}/thumbnails/{$size}_{$media->file_name}"
            );
        }

        $media->delete();
        $this->loadExistingMedia();
        $this->emit('mediaDeleted');
    }

    public function updateMediaMeta($id, $meta)
    {
        $this->model->media()
            ->where('id', $id)
            ->update(['meta' => $meta]);

        $this->loadExistingMedia();
        $this->emit('mediaMetaUpdated');
    }

    public function render()
    {
        return view('livewire.media-manager', [
            'files' => $this->files
        ]);
    }
}
