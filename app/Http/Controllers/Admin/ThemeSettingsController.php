<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThemeSettingsController extends Controller
{
    /**
     * عرض صفحة إعدادات الثيم
     */
    public function index()
    {
        $settings = Setting::where('key', 'theme_settings')->first();
        $settings = $settings ? json_decode($settings->value) : (object) [
            'default_theme' => 'real_estate',
            'features' => [],
            'customization' => []
        ];

        return view('admin.settings.theme', compact('settings'));
    }

    /**
     * تحديث إعدادات الثيم
     */
    public function update(Request $request)
    {
        $request->validate([
            'default_theme' => 'required|in:real_estate,marketplace,community',
            'features' => 'array',
            'customization' => 'array'
        ]);

        $settings = [
            'default_theme' => $request->default_theme,
            'features' => $request->features ?? [],
            'customization' => $request->customization ?? []
        ];

        Setting::updateOrCreate(
            ['key' => 'theme_settings'],
            ['value' => json_encode($settings)]
        );

        // مسح الكاش
        Cache::forget('theme_settings');

        return redirect()
            ->route('admin.settings.theme')
            ->with('success', __('admin.theme_settings_updated'));
    }
}
