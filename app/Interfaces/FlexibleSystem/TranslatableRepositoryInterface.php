<?php

namespace App\Interfaces\FlexibleSystem;

interface TranslatableRepositoryInterface extends BaseRepositoryInterface
{
    public function createWithTranslations(array $data, array $translations);
    public function updateWithTranslations(int $id, array $data, array $translations);
    public function getByLocale(string $locale = null, array $columns = ['*']);
    public function findByIdWithTranslations(int $id, string $locale = null);
    public function deleteTranslations(int $id, array $locales = []);
}
