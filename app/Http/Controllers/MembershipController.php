<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\UserMembership;
use Carbon\Carbon;
use Auth;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::where('active', 1)->get();
        $current = null;
        if (Auth::check()) {
            $current = UserMembership::where('user_id', Auth::id())
                ->where('active', 1)
                ->where('end_date', '>=', now())
                ->with('membership')
                ->first();
        }
        return view('memberships.index', compact('memberships', 'current'));
    }
    public function subscribe(Request $request)
    {
        $request->validate([
            'membership_id' => 'required|exists:memberships,id',
        ]);
        $membership = Membership::findOrFail($request->membership_id);
        $user = Auth::user();
        $start = Carbon::now();
        $end = $start->copy()->addDays($membership->duration_days);
        $userMembership = UserMembership::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'start_date' => $start,
            'end_date' => $end,
            'active' => 1,
        ]);
        // هنا يمكن ربط الدفع أو التحقق من الدفع أولاً قبل التفعيل
        return redirect()->route('memberships.index')->with('success', 'تم الاشتراك بنجاح');
    }
    public function myMembership()
    {
        $current = UserMembership::where('user_id', Auth::id())
            ->where('active', 1)
            ->where('end_date', '>=', now())
            ->with('membership')
            ->first();
        return view('memberships.my', compact('current'));
    }
}
