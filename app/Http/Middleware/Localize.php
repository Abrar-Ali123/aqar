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
            $locale = $request->segment(1);

            // Get default language from database
            $defaultLanguage = Language::where('is_default', true)
                ->where('is_active', true)
                ->first();

            // If no locale in URL or accessing root URL
            if (!$locale || $request->path() === '/') {
                if ($defaultLanguage) {
                    return redirect()->to('/' . $defaultLanguage->code . ($request->path() === '/' ? '' : '/' . $request->path()));
                }
                
                // If no default language, use fallback
                $fallbackLocale = Config::get('app.fallback_locale', 'en');
                return $this->setLocaleAndContinue($fallbackLocale, 'ltr', $request, $next);
            }

            // Check if the locale is valid
            $language = Language::where('code', $locale)
                ->where('is_active', true)
                ->first();

            if ($language) {
                return $this->setLocaleAndContinue($locale, $language->direction, $request, $next);
            }

            // If invalid locale, redirect to default language
            if ($defaultLanguage) {
                $path = $request->path();
                // Remove the invalid locale from the path
                $path = substr($path, strlen($locale) + 1) ?: '';
                return redirect()->to('/' . $defaultLanguage->code . ($path ? '/' . $path : ''));
            }

            // If no default language, use fallback
            $fallbackLocale = Config::get('app.fallback_locale', 'en');
            return $this->setLocaleAndContinue($fallbackLocale, 'ltr', $request, $next);

        } catch (\Exception $e) {
            // In case of any error, use fallback locale
            $fallbackLocale = Config::get('app.fallback_locale', 'en');
            return $this->setLocaleAndContinue($fallbackLocale, 'ltr', $request, $next);
        }
    }

    /**
     * Set locale and continue with the request
     */
    private function setLocaleAndContinue(string $locale, string $direction, Request $request, Closure $next)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        session()->put('direction', $direction);
        return $next($request);
    }
}
