<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function campaigns()
    {
        // Stub: عرض الحملات التسويقية
        return view('dashboard.marketing.campaigns');
    }
}
