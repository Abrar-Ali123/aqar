<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Session, App, Redirect};

class LocaleController extends Controller
{
    public function switch(Request $request, $locale)
    {
        // تحقق من أن اللغة مدعومة في قاعدة البيانات
        if (Language::setLocale($locale)) {
            return Redirect::back()->with('success', __('messages.language_changed'));
        }

        return Redirect::back()->with('error', __('messages.language_not_available'));
    }

    public function getAvailableLanguages()
    {
        return Language::where('is_active', true)
            ->orderBy('order')
            ->get(['code', 'name', 'direction', 'flag']);
    }
}
