<h2>فاتورة رقم #{{ $invoice->id }}</h2>
<p>العميل: {{ $invoice->customer_id }}</p>
<p>المبلغ: {{ $invoice->amount }} ريال</p>
<p>الضريبة: {{ $invoice->vat }} ريال</p>
