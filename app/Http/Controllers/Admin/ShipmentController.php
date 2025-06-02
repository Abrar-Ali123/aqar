<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ShippingCompany;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::latest()->get();
        $companies = ShippingCompany::where('active', 1)->get();
        return view('admin.shipments', compact('shipments', 'companies'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer',
            'shipping_company_id' => 'required|integer|exists:shipping_companies,id',
            'provider' => 'nullable|string',
            'tracking_number' => 'nullable|string',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
            'address' => 'required|string',
            'shipping_cost' => 'required|numeric|min:0',
        ]);
        $data['status'] = 'pending';
        Shipment::create($data);
        return redirect()->back()->with('success', 'تم إضافة شحنة جديدة');
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);
        $shipment = Shipment::findOrFail($id);
        $shipment->status = $request->status;
        $shipment->save();
        return redirect()->back()->with('success', 'تم تحديث حالة الشحنة');
    }
}
