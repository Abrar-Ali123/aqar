# دليل استخدام الحقول المترجمة في المشروع

## مقدمة
تم توحيد جميع الحقول المترجمة في النظام باستخدام مكون Blade موحد (`<x-translatable-field>`)، مما يسهل إضافة لغات جديدة وصيانة الكود.

---

## طريقة الاستخدام

### استدعاء المكون في أي نموذج:
```blade
<x-translatable-field name="name" label="اسم العنصر" :languages="config('app.locales')" required placeholder="اسم العنصر" />
```
- `name`: اسم الحقل (يجب أن يكون موحدًا في جميع اللغات).
- `label`: عنوان الحقل الظاهر للمستخدم.
- `:languages`: مصفوفة اللغات المدعومة من الكونفيج.
- `required`: إلزامية الحقل.
- `placeholder`: نص توضيحي.

### تمرير قيمة افتراضية (في التعديل):
```blade
<x-translatable-field name="name" label="اسم العنصر" :languages="config('app.locales')" :value="$item->translations['name'] ?? []" required />
```

---

## إضافة لغة جديدة
1. أضف اللغة في ملف `config/app.php` أو `config/languages.php`:
   ```php
   'locales' => ['ar', 'en', 'fr'],
   ```
2. أنشئ مجلد ترجمة جديد في `resources/lang/<code>`.
3. سيتم توليد الحقول تلقائيًا في جميع النماذج.

---

## أنواع الحقول المدعومة
- نص عادي (input)
- منطقة نص (textarea)
- دعم محرر نصوص متقدم (WYSIWYG) قيد التطوير
- دعم صور مترجمة قيد التطوير

---

## ملاحظات
- جميع التحقق من الصحة والأخطاء تتم تلقائيًا حسب اللغة.
- اتجاه النص يتغير تلقائيًا حسب اللغة.

---

## صيانة وتطوير
- أي تعديل على تصميم أو منطق الحقول يتم من خلال ملف المكون فقط.
- لإضافة نوع حقل جديد، عدل في مكون Blade أو الكلاس الخاص به.

---

## مثال عملي
```blade
<x-translatable-field name="description" label="الوصف" :languages="['ar', 'en']" type="textarea" required />
```
