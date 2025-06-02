<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FacilityPage;
use App\Models\FacilityPageVisit;

class FacilityPageVisitController extends Controller
{
    public function track($facilityId, $pageId, Request $request)
    {
        $page = FacilityPage::where('facility_id', $facilityId)->findOrFail($pageId);
        FacilityPageVisit::create([
            'facility_page_id' => $page->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ]);
        return response()->json(['status' => 'ok']);
    }
}
