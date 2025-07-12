<?php

namespace App\Repositories;

use App\Models\Facility;
use App\Interfaces\Repositories\FacilityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FacilityRepository implements FacilityRepositoryInterface
{
    public function getFeatured(string $locale, int $limit = 6): Collection
    {
        return Facility::select([
            'facilities.id',
            'facilities.header',
            'facilities.is_featured',
            'facilities.is_active',
            'facilities.created_at',
            'facility_translations.name',
            'facility_translations.description',
            'business_category_translations.name as category_name'
        ])
        ->where('facilities.is_active', true)
        ->where('facilities.is_featured', true)
        ->join('facility_translations', function($join) use ($locale) {
            $join->on('facilities.id', '=', 'facility_translations.facility_id')
                 ->where('facility_translations.locale', $locale);
        })
        ->leftJoin('business_categories', 'facilities.business_category_id', '=', 'business_categories.id')
        ->leftJoin('business_category_translations', function($join) use ($locale) {
            $join->on('business_categories.id', '=', 'business_category_translations.business_category_id')
                 ->where('business_category_translations.locale', $locale);
        })
        ->selectRaw('(SELECT COUNT(*) FROM products WHERE products.facility_id = facilities.id AND products.is_active = 1) as products_count')
        ->selectRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.facility_id = facilities.id) as reviews_avg_rating')
        ->latest('facilities.created_at')
        ->take($limit)
        ->get();
    }
}
