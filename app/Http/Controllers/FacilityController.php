<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Language;
use App\Models\FacilityType;
use App\Services\Analytics\AnalyticsManager;
use App\Services\Marketing\MarketingManager;
use App\Services\AI\ContentOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Services\ImageService;
use App\Services\TranslationService;
use App\Events\FacilityUpdated;
use App\Jobs\ProcessFacilityImages;
use App\Notifications\FacilityStatusChanged;

class FacilityController extends Controller
{
    protected $analyticsManager;
    protected $marketingManager;
    protected $imageService;
    protected $translationService;
    protected $contentOptimizer;

    public function __construct(
        AnalyticsManager $analyticsManager,
        MarketingManager $marketingManager,
        ImageService $imageService,
        TranslationService $translationService,
        ContentOptimizer $contentOptimizer
    )
    {
        $this->analyticsManager = $analyticsManager;
        $this->marketingManager = $marketingManager;
        $this->imageService = $imageService;
        $this->translationService = $translationService;
        $this->contentOptimizer = $contentOptimizer;
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('permission:create facilities')->only(['create', 'store']);
        $this->middleware('permission:edit facilities')->only(['edit', 'update', 'updateDefaultLocale', 'addLanguage', 'updateLanguagesOrder']);
        $this->middleware('permission:delete facilities')->only('destroy');
    }

    public function show($locale, $id)
    {
        // Use the provided locale if valid, otherwise fallback to app locale
        $locale = in_array($locale, config('app.supported_locales', ['ar', 'en'])) 
            ? $locale 
            : app()->getLocale();

        // محاولة العثور على المنشأة باستخدام الـ ID مع التحميل المسبق للعلاقات
        $facility = Facility::with([
            'translations' => function($q) use ($locale) {
                $q->where('locale', $locale);
            },
            'products' => function($q) {
                $q->where('products.is_active', true)
                  ->select(['products.id', 'products.facility_id', 'products.is_active', 'products.created_at'])
                  ->with(['images' => function($q) {
                      $q->select(['id', 'imageable_id', 'imageable_type', 'path', 'order'])
                        ->orderBy('order');
                  }]);
            },
            'businessSector' => function($q) use ($locale) {
                $q->with(['translations' => function($q) use ($locale) {
                    $q->where('locale', $locale);
                }]);
            },
            'businessCategory' => function($q) use ($locale) {
                $q->with(['translations' => function($q) use ($locale) {
                    $q->where('locale', $locale);
                }]);
            },
            'images' => function($q) {
                $q->select(['id', 'facility_id', 'path', 'order'])
                  ->orderBy('order');
            },
            'template' => function($q) {
                $q->select(['id', 'name', 'slug', 'layout', 'styles', 'ui_components']);
            },
            'pages' => function($q) {
                $q->where('is_active', true);
            }
        ])->findOrFail($id);

        // التحقق من أن المنشأة تدعم اللغة المطلوبة
        if (!$facility->supportsLocale($locale)) {
            $locale = $facility->default_locale;
            app()->setLocale($locale);
        }

        // تحليل أداء المنشأة
        $analytics = $this->analyticsManager->analyzeFacilityPerformance($facility);
        
        // إنشاء حملة تسويقية
        $marketingCampaign = $this->marketingManager->createMarketingCampaign($facility);

        return view('facilities.show', [
            'facility' => $facility,
            'locale' => $locale,
            'analytics' => $analytics,
            'marketingCampaign' => $marketingCampaign
        ]);
    }
}
