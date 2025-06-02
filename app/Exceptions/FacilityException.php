<?php

namespace App\Exceptions;

use Exception;

class FacilityException extends Exception
{
    /**
     * رمز الخطأ
     *
     * @var string
     */
    protected $code;

    /**
     * البيانات الإضافية
     *
     * @var array
     */
    protected $data;

    /**
     * إنشاء نموذج جديد من الاستثناء
     *
     * @param string $message
     * @param string $code
     * @param array $data
     */
    public function __construct(string $message, string $code = '', array $data = [])
    {
        parent::__construct($message);
        $this->code = $code;
        $this->data = $data;
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
     * تحويل الاستثناء إلى مصفوفة
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->code,
            'data' => $this->data
        ];
    }
}
