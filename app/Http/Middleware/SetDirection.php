<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Language;

class SetDirection
{
    public function handle(Request $request, Closure $next)
    {
        $locale = app()->getLocale();
        $language = Language::where('code', $locale)->first();
        
        if ($language) {
            session(['direction' => $language->direction]);
        } else {
            session(['direction' => 'rtl']); // Default to RTL for Arabic
        }

        return $next($request);
    }
}
