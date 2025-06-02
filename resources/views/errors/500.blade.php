<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطأ في الخادم</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            direction: rtl;
        }
        .error-container {
            max-width: 800px;
            margin: 0 auto;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .error-trace {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow-x: auto;
            white-space: pre-wrap;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>خطأ في الخادم</h1>
        
        @if(config('app.debug'))
            <div class="error-message">
                <h3>رسالة الخطأ:</h3>
                <p>{{ $message }}</p>
            </div>

            <div class="error-trace">
                <h3>تفاصيل الخطأ:</h3>
                <pre>{{ $trace }}</pre>
            </div>
        @else
            <p>عذراً، حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى لاحقاً.</p>
        @endif
    </div>
</body>
</html>
