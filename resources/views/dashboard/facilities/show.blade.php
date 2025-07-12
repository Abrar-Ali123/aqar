<x-app-layout>
    @php
        try {
            // تحميل الصفحات المفعلة
            $pages = $facility->pages()
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

            // تحليل بيانات التحليلات والتسويق
            $analyticsData = $analytics ? json_decode($analytics, true) : null;
            $campaignData = $marketingCampaign ? json_decode($marketingCampaign, true) : null;
        } catch (\Exception $e) {
            logger()->error('Error loading facility data: ' . $e->getMessage(), [
                'facility_id' => $facility->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            $pages = collect();
            $analyticsData = null;
            $campaignData = null;
        }
    @endphp

    @if($pages->isEmpty())
        <div class="alert alert-info my-4">
            {{ __('No active pages found for this facility.') }}
        </div>
    @else
        @foreach($pages as $page)
            @try
                <x-facility.section :page="$page" />
            @catch (\Exception $e)
                @php
                    logger()->error('Error rendering page: ' . $e->getMessage(), [
                        'page_id' => $page->id ?? null,
                        'facility_id' => $facility->id ?? null
                    ]);
                @endphp
                <!-- تجاهل الصفحة المعطوبة -->
            @endtry
        @endforeach
    @endif

    @if($analyticsData)
        <div 
            class="analytics-data" 
            data-analytics="{{ htmlspecialchars(json_encode($analyticsData), ENT_QUOTES, 'UTF-8') }}"
        ></div>
    @endif

    @if($campaignData)
        <div 
            class="marketing-data" 
            data-campaign="{{ htmlspecialchars(json_encode($campaignData), ENT_QUOTES, 'UTF-8') }}"
        ></div>
    @endif
</x-app-layout>