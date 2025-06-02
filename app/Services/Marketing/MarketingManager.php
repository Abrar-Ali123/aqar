<?php

namespace App\Services\Marketing;

use App\Models\Facility;
use App\Services\AI\ContentOptimizer;

class MarketingManager
{
    protected $contentOptimizer;

    public function __construct(ContentOptimizer $contentOptimizer)
    {
        $this->contentOptimizer = $contentOptimizer;
    }

    /**
     * إنشاء حملة تسويقية كاملة
     */
    public function createMarketingCampaign(Facility $facility)
    {
        // تحسين المحتوى
        $optimizedDescription = $this->contentOptimizer->optimizeDescription($facility, 'العملاء المحتملين');
        $keywords = $this->contentOptimizer->suggestKeywords($facility);

        // تحليل المنافسين
        $competitorAnalysis = $this->contentOptimizer->analyzeCompetitors($facility);

        // إنشاء محتوى لوسائل التواصل الاجتماعي
        $socialMediaContent = $this->generateSocialMediaContent($facility);

        // إنشاء حملة بريد إلكتروني
        $emailCampaign = $this->createEmailCampaign($facility);

        return [
            'optimized_description' => $optimizedDescription,
            'keywords' => $keywords,
            'competitor_analysis' => $competitorAnalysis,
            'social_media_content' => $socialMediaContent,
            'email_campaign' => $emailCampaign
        ];
    }

    /**
     * إنشاء محتوى لوسائل التواصل الاجتماعي
     */
    private function generateSocialMediaContent(Facility $facility)
    {
        $posts = [];

        // منشور عن المنتجات المميزة
        if ($facility->products->count() > 0) {
            $featuredProducts = $facility->products()
                ->orderBy('average_rating', 'desc')
                ->take(3)
                ->get();

            $posts['products'] = [
                'title' => 'منتجاتنا المميزة',
                'content' => $this->formatProductsPost($featuredProducts),
                'images' => $featuredProducts->pluck('images.0.url')->filter()
            ];
        }

        // منشور عن العروض الخاصة
        if ($facility->offers()->active()->exists()) {
            $latestOffer = $facility->offers()->active()->latest()->first();
            $posts['offer'] = [
                'title' => 'عرض خاص',
                'content' => $this->formatOfferPost($latestOffer),
                'image' => $latestOffer->image
            ];
        }

        // منشور عن تقييمات العملاء
        if ($facility->reviews()->count() > 0) {
            $bestReview = $facility->reviews()
                ->where('rating', 5)
                ->where('is_approved', true)
                ->latest()
                ->first();

            if ($bestReview) {
                $posts['review'] = [
                    'title' => 'ماذا يقول عملاؤنا',
                    'content' => $this->formatReviewPost($bestReview),
                    'rating' => $bestReview->rating
                ];
            }
        }

        return $posts;
    }

    /**
     * إنشاء حملة بريد إلكتروني
     */
    private function createEmailCampaign(Facility $facility)
    {
        return [
            'welcome_email' => [
                'subject' => "مرحباً بك في {$facility->name}",
                'content' => $this->generateWelcomeEmail($facility)
            ],
            'promotion_email' => [
                'subject' => "عروض حصرية من {$facility->name}",
                'content' => $this->generatePromotionalEmail($facility)
            ],
            'newsletter' => [
                'subject' => "آخر الأخبار من {$facility->name}",
                'content' => $this->generateNewsletter($facility)
            ]
        ];
    }

    /**
     * تنسيق منشور المنتجات
     */
    private function formatProductsPost($products)
    {
        return "اكتشف منتجاتنا المميزة:\n\n" . 
               $products->map(function ($product) {
                   return "- {$product->name}: {$product->description}";
               })->join("\n");
    }

    /**
     * تنسيق منشور العروض
     */
    private function formatOfferPost($offer)
    {
        return "عرض خاص! 🎉\n" .
               "{$offer->title}\n" .
               "{$offer->description}\n" .
               "العرض ساري حتى: {$offer->end_date}";
    }

    /**
     * تنسيق منشور التقييمات
     */
    private function formatReviewPost($review)
    {
        return "ما يقوله عملاؤنا:\n\n" .
               "\"{$review->comment}\"\n" .
               "- {$review->user->name}";
    }

    /**
     * إنشاء محتوى البريد الترحيبي
     */
    private function generateWelcomeEmail($facility)
    {
        return [
            'greeting' => "مرحباً بك في {$facility->name}",
            'intro' => "نحن سعداء بانضمامك إلى عائلتنا",
            'content' => [
                'about' => $facility->description,
                'services' => $facility->services->pluck('name'),
                'contact' => [
                    'phone' => $facility->phone,
                    'email' => $facility->email,
                    'address' => $facility->address
                ]
            ]
        ];
    }

    /**
     * إنشاء محتوى البريد الترويجي
     */
    private function generatePromotionalEmail($facility)
    {
        $offers = $facility->offers()->active()->get();
        
        return [
            'greeting' => "عروض حصرية لك",
            'offers' => $offers->map(function ($offer) {
                return [
                    'title' => $offer->title,
                    'description' => $offer->description,
                    'discount' => $offer->discount,
                    'expires' => $offer->end_date
                ];
            })
        ];
    }

    /**
     * إنشاء محتوى النشرة الإخبارية
     */
    private function generateNewsletter($facility)
    {
        return [
            'greeting' => "آخر الأخبار من {$facility->name}",
            'sections' => [
                'new_products' => $facility->products()->latest()->take(5)->get(),
                'upcoming_events' => $facility->events()->upcoming()->take(3)->get(),
                'latest_news' => $facility->news()->latest()->take(3)->get()
            ]
        ];
    }
}
