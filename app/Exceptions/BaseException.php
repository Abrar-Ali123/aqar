<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

abstract class BaseException extends Exception
{
    /**
     * رمز الخطأ
     *
     * @var string
     */
    protected $errorCode;

    /**
     * البيانات الإضافية
     *
     * @var array
     */
    protected $data;

    /**
     * مستوى السجل
     *
     * @var string
     */
    protected $logLevel = 'error';

    /**
     * إنشاء نموذج جديد من الاستثناء
     *
     * @param string $message
     * @param string $errorCode
     * @param array $data
     */
    public function __construct(string $message, string $errorCode = '', array $data = [])
    {
        parent::__construct($message);
        $this->errorCode = $errorCode;
        $this->data = $data;
    }

    /**
     * الحصول على رمز الخطأ
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * الحصول على البيانات الإضافية
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * الحصول على مستوى السجل
     *
     * @return string
     */
    public function getLogLevel(): string
    {
        return $this->logLevel;
    }

    /**
     * تحويل الاستثناء إلى استجابة JSON
     *
     * @return JsonResponse
     */
    public function toResponse(): JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => $this->getMessage(),
            'code' => $this->errorCode,
            'data' => $this->data
        ], $this->getHttpStatusCode());
    }

    /**
     * الحصول على رمز حالة HTTP
     *
     * @return int
     */
    abstract public function getHttpStatusCode(): int;
}
