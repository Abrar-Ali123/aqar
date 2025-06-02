<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGatewayFee;

class PaymentGatewayFeeController extends Controller
{
    public function index()
    {
        $fees = PaymentGatewayFee::all();
        return view('admin.gateway-fees', compact('fees'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'gateway' => 'required|string',
            'fee_percent' => 'numeric|min:0',
            'fee_fixed' => 'numeric|min:0',
        ]);
        PaymentGatewayFee::updateOrCreate(
            ['gateway' => $data['gateway']],
            $data
        );
        return redirect()->back()->with('success', 'تم حفظ العمولة');
    }
}
