<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{App, Config, Route, Cache};
use App\Models\Language;

class Localize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Get locale from URL
            $locale = $request->route('locale');

            // If no locale in URL, use default language
            if (!$locale) {
                $defaultLanguage = Language::where('is_default', true)
                    ->where('is_active', true)
                    ->first();

                if ($defaultLanguage) {
                    return redirect()->to('/' . $defaultLanguage->code);
                }

                // If no default language, use fallback
                $fallbackLocale = Config::get('app.fallback_locale', 'en');
                App::setLocale($fallbackLocale);
                session()->put('locale', $fallbackLocale);
                session()->put('direction', 'rtl');
                return $next($request);
            }

            // Get current language
            $language = Language::where('code', $locale)
                ->where('is_active', true)
                ->first();

            if ($language) {
                App::setLocale($locale);
                session()->put('locale', $locale);
                session()->put('direction', $language->direction);
                return $next($request);
            }

            // If invalid locale, redirect to default language
            $defaultLanguage = Language::where('is_default', true)
                ->where('is_active', true)
                ->first();

            if ($defaultLanguage) {
                return redirect()->to('/' . $defaultLanguage->code);
            }

            // If no default language, use fallback
            $fallbackLocale = Config::get('app.fallback_locale', 'en');
            App::setLocale($fallbackLocale);
            session()->put('locale', $fallbackLocale);
            session()->put('direction', 'rtl');
            return $next($request);

        } catch (\Exception $e) {
            // In case of any error, use fallback locale
            $fallbackLocale = Config::get('app.fallback_locale', 'en');
            App::setLocale($fallbackLocale);
            session()->put('locale', $fallbackLocale);
            session()->put('direction', 'rtl');
            return $next($request);
        }
    }
}
