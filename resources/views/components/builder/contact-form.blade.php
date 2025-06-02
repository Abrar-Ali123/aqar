<div class="contact-form-component" x-data="{
    fields: @entangle('content.fields'),
    submitted: false,
    loading: false,
    success: false,
    error: null,

    addField() {
        this.fields.push({
            type: 'text',
            label: '',
            name: '',
            required: false,
            placeholder: '',
            options: []
        });
    },

    removeField(index) {
        this.fields.splice(index, 1);
        this.updateFields();
    },

    updateFields() {
        $wire.emit('componentSettingsUpdated', '{{ $componentId }}', {
            content: { fields: this.fields }
        });
    },

    async submitForm(event) {
        event.preventDefault();
        this.loading = true;
        this.error = null;

        const formData = new FormData(event.target);
        
        try {
            const response = await fetch('/api/contact-form/submit', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            if (response.ok) {
                this.success = true;
                this.submitted = true;
                event.target.reset();
            } else {
                this.error = data.message || 'حدث خطأ أثناء إرسال النموذج';
            }
        } catch (err) {
            this.error = 'حدث خطأ أثناء إرسال النموذج';
        }

        this.loading = false;
    }
}">
    <!-- نموذج الاتصال -->
    <form x-show="!submitted" 
          x-on:submit="submitForm"
          class="space-y-4">
        
        <!-- حقول النموذج -->
        <template x-for="(field, index) in fields" :key="index">
            <div class="space-y-2">
                <!-- حقل نصي -->
                <template x-if="field.type === 'text'">
                    <div>
                        <label x-text="field.label" class="block text-sm font-medium text-gray-700"></label>
                        <input :type="field.type"
                               :name="field.name"
                               :placeholder="field.placeholder"
                               :required="field.required"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </template>

                <!-- منطقة نص -->
                <template x-if="field.type === 'textarea'">
                    <div>
                        <label x-text="field.label" class="block text-sm font-medium text-gray-700"></label>
                        <textarea :name="field.name"
                                  :placeholder="field.placeholder"
                                  :required="field.required"
                                  rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </template>

                <!-- قائمة منسدلة -->
                <template x-if="field.type === 'select'">
                    <div>
                        <label x-text="field.label" class="block text-sm font-medium text-gray-700"></label>
                        <select :name="field.name"
                                :required="field.required"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <template x-for="option in field.options" :key="option">
                                <option x-text="option"></option>
                            </template>
                        </select>
                    </div>
                </template>

                <!-- خانة اختيار -->
                <template x-if="field.type === 'checkbox'">
                    <div class="flex items-center gap-2">
                        <input :name="field.name"
                               type="checkbox"
                               :required="field.required"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <label x-text="field.label" class="text-sm font-medium text-gray-700"></label>
                    </div>
                </template>
            </div>
        </template>

        <!-- رسالة الخطأ -->
        <div x-show="error" 
             x-text="error"
             class="text-red-600 text-sm">
        </div>

        <!-- زر الإرسال -->
        <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                x-bind:disabled="loading">
            <span x-show="!loading">إرسال</span>
            <span x-show="loading" class="flex items-center gap-2">
                <i class="fas fa-spinner fa-spin"></i>
                جاري الإرسال...
            </span>
        </button>
    </form>

    <!-- رسالة النجاح -->
    <div x-show="submitted && success" 
         class="bg-green-50 border border-green-200 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="mr-3">
                <p class="text-sm font-medium text-green-800">
                    تم إرسال النموذج بنجاح
                </p>
            </div>
        </div>
    </div>

    <!-- وضع التحرير -->
    <div x-show="$wire.isEditing" class="mt-8 space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium">تحرير الحقول</h3>
            <button 
                class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700"
                x-on:click="addField">
                إضافة حقل
            </button>
        </div>

        <!-- قائمة الحقول -->
        <div class="space-y-4">
            <template x-for="(field, index) in fields" :key="index">
                <div class="border rounded-md p-4 space-y-3">
                    <div class="flex justify-between">
                        <h4 class="font-medium" x-text="field.label || 'حقل جديد'"></h4>
                        <button 
                            class="text-red-600 hover:text-red-700"
                            x-on:click="removeField(index)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- نوع الحقل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">النوع</label>
                            <select 
                                x-model="field.type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                x-on:change="updateFields">
                                <option value="text">نص</option>
                                <option value="textarea">منطقة نص</option>
                                <option value="select">قائمة منسدلة</option>
                                <option value="checkbox">خانة اختيار</option>
                            </select>
                        </div>

                        <!-- اسم الحقل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">الاسم</label>
                            <input 
                                type="text"
                                x-model="field.name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                x-on:change="updateFields">
                        </div>

                        <!-- عنوان الحقل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">العنوان</label>
                            <input 
                                type="text"
                                x-model="field.label"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                x-on:change="updateFields">
                        </div>

                        <!-- النص المؤقت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">النص المؤقت</label>
                            <input 
                                type="text"
                                x-model="field.placeholder"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                x-on:change="updateFields">
                        </div>
                    </div>

                    <!-- خيارات القائمة المنسدلة -->
                    <div x-show="field.type === 'select'" class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">الخيارات (مفصولة بفواصل)</label>
                        <input 
                            type="text"
                            x-model="field.options"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            x-on:change="updateFields">
                    </div>

                    <!-- حقل مطلوب -->
                    <div class="flex items-center gap-2">
                        <input 
                            type="checkbox"
                            x-model="field.required"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            x-on:change="updateFields">
                        <label class="text-sm font-medium text-gray-700">حقل مطلوب</label>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
