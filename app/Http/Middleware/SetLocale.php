<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LocaleService;

class SetLocale
{
    /**
     * المسارات التي لا تحتاج إلى بريفكس اللغة
     */
    protected $excludedPaths = [
        '_debugbar',
        'api',
        'webhooks',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // تجاهل المسارات المستثناة
        $firstSegment = $request->segment(1);
        if ($firstSegment && in_array($firstSegment, $this->excludedPaths)) {
            return $next($request);
        }

        $locale = $request->segment(1);
        
        if ($locale) {
            app(LocaleService::class)->setLocale($locale);
        }

        return $next($request);
    }
}
