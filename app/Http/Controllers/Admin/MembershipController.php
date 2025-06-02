<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membership;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::all();
        return view('admin.memberships', compact('memberships'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'active' => 'boolean',
        ]);
        $data['features'] = $data['features'] ?? [];
        Membership::create($data);
        return redirect()->back()->with('success', 'تمت إضافة عضوية جديدة');
    }
    public function toggle($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->active = !$membership->active;
        $membership->save();
        return redirect()->back()->with('success', 'تم تحديث حالة العضوية');
    }
}
