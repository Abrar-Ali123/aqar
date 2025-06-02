<?php
namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index()
    {
        $languages = Language::all();
        // جلب جميع الترجمات من ملفات lang أو من قاعدة البيانات
        // مثال: $translations = ...;
        return view('dashboard.translations.index', compact('languages'));
    }

    public function update(Request $request)
    {
        // تحديث الترجمة
        // ...
        return back()->with('success', 'تم تحديث الترجمة بنجاح');
    }
}
