<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\FacilityAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackAnalytics
{
    public function handle(Request $request, Closure $next)
    {
        // إذا كانت الصفحة تحتوي على منشأة
        if ($request->route('facility')) {
            $facility = $request->route('facility');
            
            // إنشاء أو استرجاع معرف الزائر
            $visitorId = $request->cookie('visitor_id') ?? Str::uuid();
            
            // تسجيل الزيارة
            FacilityAnalytics::create([
                'facility_id' => $facility->id,
                'visitor_id' => $visitorId,
                'device_type' => $this->getDeviceType($request),
                'referrer' => $request->headers->get('referer'),
                'page_views' => 1,
                'time_on_page' => 0, // سيتم تحديثه عبر JavaScript
                'duration' => 0, // سيتم تحديثه عبر JavaScript
                'interaction_data' => [
                    'page_views' => 1,
                    'clicks' => [],
                    'scrolls' => [],
                    'forms' => []
                ]
            ]);

            // تخزين معرف الزائر في الكوكيز
            if (!$request->cookie('visitor_id')) {
                cookie()->queue('visitor_id', $visitorId, 43200); // 30 يوم
            }
        }

        return $next($request);
    }

    private function getDeviceType(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
            return 'tablet';
        }
        
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $userAgent)) {
            return 'mobile';
        }
        
        return 'desktop';
    }
}
