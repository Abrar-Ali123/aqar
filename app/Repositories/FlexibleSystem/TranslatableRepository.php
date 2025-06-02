<?php

namespace App\Repositories\FlexibleSystem;

use App\Interfaces\FlexibleSystem\TranslatableRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class TranslatableRepository extends BaseRepository implements TranslatableRepositoryInterface
{
    public function createWithTranslations(array $data, array $translations): Model
    {
        $model = $this->create($data);
        
        foreach ($translations as $locale => $translationData) {
            $model->saveTranslation($translationData, $locale);
        }
        
        return $model->load('translations');
    }

    public function updateWithTranslations(int $id, array $data, array $translations): Model
    {
        $model = $this->findById($id);
        $model->update($data);
        
        foreach ($translations as $locale => $translationData) {
            $model->saveTranslation($translationData, $locale);
        }
        
        return $model->load('translations');
    }

    public function getByLocale(string $locale = null, array $columns = ['*']): Collection
    {
        return $this->model->with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale ?: app()->getLocale());
        }])->get($columns);
    }

    public function findByIdWithTranslations(int $id, string $locale = null): ?Model
    {
        return $this->model->with(['translations' => function ($query) use ($locale) {
            if ($locale) {
                $query->where('locale', $locale);
            }
        }])->find($id);
    }

    public function deleteTranslations(int $id, array $locales = []): bool
    {
        $model = $this->findById($id);
        $query = $model->translations();
        
        if (!empty($locales)) {
            $query->whereIn('locale', $locales);
        }
        
        return $query->delete();
    }
}
