<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إعدادات الذكاء الاصطناعي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.ai-settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            حالة الميزة
                        </label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio" name="is_enabled" value="1" {{ $settings->is_enabled ? 'checked' : '' }}>
                                <span class="mr-2">مفعلة</span>
                            </label>
                            <label class="inline-flex items-center mr-6">
                                <input type="radio" class="form-radio" name="is_enabled" value="0" {{ !$settings->is_enabled ? 'checked' : '' }}>
                                <span class="mr-2">معطلة</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            تاريخ انتهاء الاشتراك
                        </label>
                        <input type="date" name="subscription_ends_at" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               value="{{ $settings->subscription_ends_at?->format('Y-m-d') }}">
                        <p class="text-sm text-gray-500 mt-1">اتركه فارغاً إذا كان الاشتراك غير محدود</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            نموذج API
                        </label>
                        <select name="api_model" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="gpt-4" {{ $settings->api_model === 'gpt-4' ? 'selected' : '' }}>GPT-4 (أفضل أداء، تكلفة أعلى)</option>
                            <option value="gpt-3.5-turbo" {{ $settings->api_model === 'gpt-3.5-turbo' ? 'selected' : '' }}>GPT-3.5 Turbo (أداء جيد، تكلفة أقل)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
