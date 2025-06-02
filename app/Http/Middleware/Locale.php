<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Language;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من وجود معامل اللغة في الرابط
        $locale = $request->route('locale');
        
        if (!$locale) {
            return redirect('/ar');
        }
        
        // التحقق من صحة اللغة
        $language = Language::where('code', $locale)->first();
        
        if (!$language) {
            return redirect('/ar');
        }
        
        // تعيين اللغة الحالية
        app()->setLocale($locale);
        
        return $next($request);
    }
}
