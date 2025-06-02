<?php

namespace App\Services\AI;

use OpenAI\Client;
use App\Models\Facility;

class ContentOptimizer
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * تحسين وصف المنشأة
     */
    public function optimizeDescription(Facility $facility, string $targetAudience)
    {
        $prompt = "تحسين وصف المنشأة التالية لجذب {$targetAudience}:\n{$facility->description}";
        
        $response = $this->client->completions()->create([
            'model' => 'gpt-4',
            'prompt' => $prompt,
            'max_tokens' => 500
        ]);

        return $response['choices'][0]['text'];
    }

    /**
     * اقتراح كلمات مفتاحية للمنشأة
     */
    public function suggestKeywords(Facility $facility)
    {
        $prompt = "اقتراح كلمات مفتاحية مناسبة للمنشأة التالية:\n" . 
                 "الاسم: {$facility->name}\n" .
                 "الوصف: {$facility->description}\n" .
                 "النشاط: {$facility->businessCategory->name}";

        $response = $this->client->completions()->create([
            'model' => 'gpt-4',
            'prompt' => $prompt,
            'max_tokens' => 200
        ]);

        return explode(',', $response['choices'][0]['text']);
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
