<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiSetting;
use Illuminate\Http\Request;

class AiSettingsController extends Controller
{
    public function index()
    {
        $settings = AiSetting::first() ?? new AiSetting();
        return view('admin.ai-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'is_enabled' => 'required|boolean',
            'subscription_ends_at' => 'nullable|date',
            'api_model' => 'required|in:gpt-4,gpt-3.5-turbo'
        ]);

        $settings = AiSetting::first() ?? new AiSetting();
        $settings->fill($request->only([
            'is_enabled',
            'subscription_ends_at',
            'api_model'
        ]));
        $settings->save();

        return redirect()->back()->with('success', 'تم تحديث إعدادات الذكاء الاصطناعي بنجاح');
    }
}
