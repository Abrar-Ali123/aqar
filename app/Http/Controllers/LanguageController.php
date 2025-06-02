<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * تبديل لغة التطبيق
     */
    public function switch($locale)
    {
        $language = \App\Models\Language::where('code', $locale)
            ->where('is_active', true)
            ->first();

        if (!$language) {
            return back()->with('error', 'اللغة غير متوفرة');
        }

        // حفظ اللغة المحددة في الجلسة
        session()->put('locale', $locale);
        session()->put('direction', $language->direction);

        return redirect()->back();
    }
}
