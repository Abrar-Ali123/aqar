<?php

namespace App\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface FacilityRepositoryInterface
{
    public function getFeatured(string $locale, int $limit = 6): Collection;
}
