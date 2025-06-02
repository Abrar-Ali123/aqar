<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BulkPayment;
use App\Models\User;
use App\Models\PaymentTransaction;

class BulkPaymentController extends Controller
{
    public function index()
    {
        $bulkPayments = BulkPayment::latest()->get();
        return view('admin.bulk-payments', compact('bulkPayments'));
    }
    public function create(Request $request)
    {
        $data = $request->validate([
            'users' => 'required|array',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string',
        ]);
        $reference = uniqid('bulk_');
        $total = $data['amount'] * count($data['users']);
        $bulk = BulkPayment::create([
            'reference' => $reference,
            'created_by' => auth()->id(),
            'total_amount' => $total,
            'currency' => $data['currency'],
            'status' => 'pending',
            'details' => [ 'users' => $data['users'], 'amount' => $data['amount'] ],
        ]);
        foreach ($data['users'] as $userId) {
            PaymentTransaction::create([
                'gateway' => 'bulk',
                'transaction_id' => uniqid('bulk_tx_'),
                'status' => 'pending',
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'user_id' => $userId,
                'details' => [ 'bulk_reference' => $reference ],
            ]);
        }
        return redirect()->back()->with('success', 'تم إنشاء دفعة جماعية');
    }
}
