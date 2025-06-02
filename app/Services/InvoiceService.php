<?php
namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function generatePDF(Invoice $invoice)
    {
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        $path = 'invoices/invoice_'.$invoice->id.'.pdf';
        $pdf->save(storage_path('app/public/'.$path));
        $invoice->update(['pdf_path' => $path]);
        return $path;
    }

    public static function generatePDF($transaction)
    {
        $pdf = \PDF::loadView('invoices.payment', ['transaction' => $transaction]);
        $filename = 'invoice_' . $transaction->id . '.pdf';
        $path = storage_path('app/invoices/' . $filename);
        $pdf->save($path);
        return $path;
    }
}
