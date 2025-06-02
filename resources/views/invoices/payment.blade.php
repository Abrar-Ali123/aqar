<html>
<head>
    <meta charset="utf-8">
    <title>فاتورة دفع</title>
    <style>
        body { font-family: 'Cairo', Arial, sans-serif; }
        .header { font-size: 22px; font-weight: bold; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    <div class="header">فاتورة دفع إلكتروني</div>
    <table class="table">
        <tr><th>رقم العملية</th><td>{{ $transaction->id }}</td></tr>
        <tr><th>المستخدم</th><td>{{ optional($transaction->user)->name ?? '-' }}</td></tr>
        <tr><th>المبلغ</th><td>{{ $transaction->amount }} {{ $transaction->currency }}</td></tr>
        <tr><th>البوابة</th><td>{{ $transaction->gateway }}</td></tr>
        <tr><th>الحالة</th><td>{{ $transaction->status }}</td></tr>
        <tr><th>التاريخ</th><td>{{ $transaction->created_at }}</td></tr>
    </table>
</body>
</html>
