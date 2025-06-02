<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Storage;

class PaymentExportController extends Controller
{
    public function exportCsv()
    {
        $filename = 'payments_export_' . date('Ymd_His') . '.csv';
        $transactions = PaymentTransaction::all();
        $handle = fopen(storage_path('app/' . $filename), 'w');
        fputcsv($handle, ['ID', 'User', 'Amount', 'Currency', 'Gateway', 'Status', 'Date']);
        foreach ($transactions as $tx) {
            fputcsv($handle, [
                $tx->id,
                optional($tx->user)->name,
                $tx->amount,
                $tx->currency,
                $tx->gateway,
                $tx->status,
                $tx->created_at
            ]);
        }
        fclose($handle);
        return response()->download(storage_path('app/' . $filename))->deleteFileAfterSend(true);
    }
}
