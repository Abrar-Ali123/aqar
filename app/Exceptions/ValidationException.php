<?php

namespace App\Exceptions;

class ValidationException extends BaseException
{
    /**
     * إنشاء نموذج جديد من الاستثناء
     *
     * @param array $errors أخطاء التحقق
     * @param string $message رسالة الخطأ
     */
    public function __construct(array $errors, string $message = 'بيانات غير صالحة')
    {
        parent::__construct($message, 'VALIDATION_ERROR', ['errors' => $errors]);
        $this->logLevel = 'warning';
    }

    /**
     * الحصول على رمز حالة HTTP
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return 422;
    }
}
