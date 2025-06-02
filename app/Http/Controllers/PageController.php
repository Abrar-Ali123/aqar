<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * عرض صفحة من نحن
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * عرض صفحة اتصل بنا
     */
    public function contact()
    {
        return view('pages.contact');
    }
}
