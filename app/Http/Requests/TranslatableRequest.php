<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TranslatableRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        $languages = Language::all();
        
        // الحقول المترجمة التي نريد التحقق منها
        $translatableFields = $this->translatableFields ?? [];
        
        foreach ($translatableFields as $field => $fieldRules) {
            foreach ($languages as $language) {
                $isRequired = $language->is_required;
                $fieldName = "{$field}_{$language->code}";
                
                // إذا كانت اللغة إجبارية، نضيف قاعدة required
                if ($isRequired) {
                    $fieldRules = array_merge(['required'], (array)$fieldRules);
                } else {
                    $fieldRules = array_merge(['nullable'], (array)$fieldRules);
                }
                
                $rules[$fieldName] = $fieldRules;
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $languages = Language::all();
        
        foreach ($this->translatableFields ?? [] as $field => $rules) {
            foreach ($languages as $language) {
                $fieldName = "{$field}_{$language->code}";
                $langName = $language->native_name;
                
                $messages["{$fieldName}.required"] = "حقل {$field} باللغة {$langName} مطلوب.";
                // يمكن إضافة المزيد من رسائل الخطأ حسب القواعد المستخدمة
            }
        }

        return $messages;
    }
}
