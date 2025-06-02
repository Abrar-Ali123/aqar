@props(['data', 'facility'])

<div class="contact-form" x-data="{ 
    formData: {
        name: '',
        email: '',
        phone: '',
        message: ''
    },
    loading: false,
    success: false,
    error: null,
    async submitForm() {
        this.loading = true;
        this.error = null;
        this.success = false;

        try {
            const response = await fetch('{{ route('facility.contact', ['facility' => $facility->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(this.formData)
            });

            const result = await response.json();

            if (response.ok) {
                this.success = true;
                this.formData = { name: '', email: '', phone: '', message: '' };
            } else {
                this.error = result.message || 'حدث خطأ أثناء إرسال الرسالة';
            }
        } catch (e) {
            this.error = 'حدث خطأ أثناء إرسال الرسالة';
        } finally {
            this.loading = false;
        }
    }
}">
    @if(isset($data['title']))
        <h2 class="text-2xl font-bold mb-4">{{ $data['title'] }}</h2>
    @endif

    <form @submit.prevent="submitForm" class="space-y-4">
        {{-- Success Message --}}
        <div x-show="success" 
             class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4"
             x-transition>
            تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.
        </div>

        {{-- Error Message --}}
        <div x-show="error" 
             class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4"
             x-transition>
            <span x-text="error"></span>
        </div>

        {{-- Name Field --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
            <input type="text" 
                   id="name" 
                   x-model="formData.name" 
                   required
                   class="form-input w-full rounded-lg"
                   :disabled="loading">
        </div>

        {{-- Email Field --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">البريد الإلكتروني</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input type="email" name="email" id="email" required
                       class="block w-full pr-10 border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                       :disabled="isSubmitting">
            </div>
        </div>

        <!-- Phone Field -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الجوال</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-phone text-gray-400"></i>
                </div>
                <input type="tel" name="phone" id="phone" required
                       class="block w-full pr-10 border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                       :disabled="isSubmitting">
            </div>
        </div>

        <!-- Message Field -->
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">الرسالة</label>
            <div class="mt-1">
                <textarea id="message" name="message" rows="4" required
                          class="block w-full border-gray-300 dark:border-gray-600 rounded-md focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                          :disabled="isSubmitting"></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                    class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150 disabled:opacity-50 disabled:cursor-not-allowed dark:focus:ring-offset-gray-900"
                    :disabled="isSubmitting">
                <span x-show="!isSubmitting">إرسال الرسالة</span>
                <span x-show="isSubmitting" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    جاري الإرسال...
                </span>
            </button>
        </div>
    </form>
</div>
