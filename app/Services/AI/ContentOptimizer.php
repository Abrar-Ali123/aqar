<?php

namespace App\Services\AI;

use OpenAI\Client;
use App\Models\Facility;

use App\Models\AiSetting;

class ContentOptimizer
{
    protected $client;
    protected $isEnabled;
    protected $apiModel;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->isEnabled = AiSetting::isFeatureEnabled();
        $this->apiModel = AiSetting::getApiModel();
    }

    /**
     * تحسين وصف المنشأة
     */
    public function optimizeDescription(Facility $facility, string $targetAudience)
    {
        if (!$this->isEnabled) {
            return $facility->description;
        }

        $prompt = "تحسين وصف المنشأة التالية لجذب {$targetAudience}:\n{$facility->description}";
        
        try {
            $response = $this->client->completions()->create([
                'model' => $this->apiModel,
                'prompt' => $prompt,
                'max_tokens' => 500
            ]);

            return $response['choices'][0]['text'];
        } catch (\Exception $e) {
            \Log::error('OpenAI Error: ' . $e->getMessage());
            return $facility->description;
        }
    }

    /**
     * اقتراح كلمات مفتاحية للمنشأة
     */
    public function suggestKeywords(Facility $facility)
    {
        if (!$this->isEnabled) {
            // إرجاع كلمات مفتاحية افتراضية من اسم المنشأة والنشاط
            $keywords = [$facility->name];
            if ($facility->businessCategory) {
                $keywords[] = $facility->businessCategory->name;
            }
            return array_unique($keywords);
        }

        $prompt = "اقتراح كلمات مفتاحية مناسبة للمنشأة التالية:\n" . 
                 "الاسم: {$facility->name}\n" .
                 "الوصف: {$facility->description}\n";

        if ($facility->businessCategory) {
            $prompt .= "النشاط: {$facility->businessCategory->name}\n";
        }

        try {
            $response = $this->client->completions()->create([
                'model' => $this->apiModel,
                'prompt' => $prompt,
                'max_tokens' => 200
            ]);

            return explode(',', $response['choices'][0]['text']);
        } catch (\Exception $e) {
            \Log::error('OpenAI Error: ' . $e->getMessage());
            $keywords = [$facility->name];
            if ($facility->businessCategory) {
                $keywords[] = $facility->businessCategory->name;
            }
            return array_unique($keywords);
        }
    }

    /**
     * تحليل المنافسين واقتراح تحسينات
     */
    public function analyzeCompetitors(Facility $facility)
    {
        $competitors = Facility::where('business_category_id', $facility->business_category_id)
            ->where('id', '!=', $facility->id)
            ->with(['reviews', 'products'])
            ->get();

        $competitorData = $competitors->map(function ($competitor) {
            return [
                'rating' => $competitor->average_rating,
                'reviews_count' => $competitor->reviews_count,
                'products_count' => $competitor->products->count(),
                'description_length' => strlen($competitor->description)
            ];
        });

        return [
            'average_rating' => $competitorData->avg('rating'),
            'average_reviews' => $competitorData->avg('reviews_count'),
            'average_products' => $competitorData->avg('products_count'),
            'description_length' => $competitorData->avg('description_length'),
            'recommendations' => $this->generateRecommendations($facility, $competitorData)
        ];
    }

    /**
     * توليد توصيات لتحسين المنشأة
     */
    private function generateRecommendations(Facility $facility, $competitorData)
    {
        $recommendations = [];

        if ($facility->average_rating < $competitorData->avg('rating')) {
            $recommendations[] = 'تحسين جودة الخدمة لزيادة التقييمات';
        }

        if ($facility->reviews_count < $competitorData->avg('reviews_count')) {
            $recommendations[] = 'تشجيع العملاء على كتابة المزيد من التقييمات';
        }

        if ($facility->products->count() < $competitorData->avg('products_count')) {
            $recommendations[] = 'إضافة المزيد من المنتجات لتوسيع العرض';
        }

        return $recommendations;
    }
}
