<?php

namespace App\Services;

use App\Interfaces\Repositories\FacilityRepositoryInterface;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Models\BusinessSector;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class HomePageService
{
    public function __construct(
        private FacilityRepositoryInterface $facilityRepository,
        private ProductRepositoryInterface $productRepository
    ) {}

    public function getFeaturedContent(string $locale): array
    {
        try {
            // Get business sectors with translations
            $businessSectors = BusinessSector::select([
                'business_sectors.id',
                'business_sectors.icon',
                'business_sector_translations.name'
            ])
            ->join('business_sector_translations', function($join) use ($locale) {
                $join->on('business_sectors.id', '=', 'business_sector_translations.business_sector_id')
                     ->where('business_sector_translations.locale', $locale);
            })
            ->where('business_sectors.is_active', true)
            ->orderBy('business_sectors.order')
            ->take(12)
            ->get();

            return [
                'businessSectors' => $businessSectors,
                'featuredFacilities' => $this->facilityRepository->getFeatured($locale),
                'featuredProducts' => $this->productRepository->getFeatured()
            ];
        } catch (\Exception $e) {
            Log::error('Error in HomePageService: ' . $e->getMessage());
            return [
                'businessSectors' => collect(),
                'featuredFacilities' => collect(),
                'featuredProducts' => collect()
            ];
        }
    }
}
