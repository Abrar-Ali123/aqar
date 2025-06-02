<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingCompany;

class ShippingCompanyController extends Controller
{
    public function index()
    {
        $companies = ShippingCompany::all();
        return view('admin.shipping-companies', compact('companies'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'logo' => 'nullable|image',
            'active' => 'boolean',
        ]);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('shipping-logos', 'public');
        }
        ShippingCompany::create($data);
        return redirect()->back()->with('success', 'تمت إضافة شركة شحن جديدة');
    }
    public function toggle($id)
    {
        $company = ShippingCompany::findOrFail($id);
        $company->active = !$company->active;
        $company->save();
        return redirect()->back()->with('success', 'تم تحديث حالة الشركة');
    }
}
