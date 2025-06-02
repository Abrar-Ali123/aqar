<?php

namespace App\Exceptions;

class AuthorizationException extends BaseException
{
    /**
     * إنشاء نموذج جديد من الاستثناء
     *
     * @param string $message رسالة الخطأ
     * @param string $errorCode رمز الخطأ
     * @param array $data بيانات إضافية
     */
    public function __construct(string $message = 'غير مصرح', string $errorCode = 'UNAUTHORIZED', array $data = [])
    {
        parent::__construct($message, $errorCode, $data);
        $this->logLevel = 'warning';
    }

    /**
     * الحصول على رمز حالة HTTP
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return 403;
    }
}
