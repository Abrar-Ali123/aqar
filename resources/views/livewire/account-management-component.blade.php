<div class="min-h-screen bg-gray-100">
    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6 bg-white p-8 rounded-lg shadow-md">
            @isset($message)
                @if($message)
                    <div class="p-4 rounded-md {{ str_contains($message, 'خطأ') ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">
                        {{ $message }}
                    </div>
                @endif
            @endisset

            @isset($step)
            @if ($step === 'phone')
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">تسجيل الدخول</h2>
                    <p class="text-gray-600 mt-2">أدخل رقم جوالك للمتابعة</p>
                </div>

                <form action="{{ route('account.send-code') }}" method="POST" class="space-y-6">
                    <div>
                        <label for="mobile" class="sr-only">رقم الجوال</label>
                        <div class="mt-1">
                            <input name="mobile" type="tel" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="05XXXXXXXX" dir="ltr">
                        </div>
                        @error('mobile')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        إرسال رمز التحقق
                    </button>
                </form>

            @elseif ($step === 'verify')
                <div class="text-center space-y-4">
                    <h2 class="text-2xl font-bold text-gray-900">التحقق من رقم الجوال</h2>
                    <p class="text-gray-600">تم إرسال رمز التحقق إلى @isset($mobile){{ $mobile }}@endisset</p>

                    <div class="mt-1">
                        <input name="code" type="text" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-center"
                            placeholder="XXXXXX" maxlength="6" dir="ltr">
                        @error('verificationCode')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                            class="mt-4 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            تحقق
                        </button>
                    </div>
                </div>

            @elseif ($step === 'name')
                <div class="text-center space-y-4">
                    <h2 class="text-2xl font-bold text-gray-900">أكمل بياناتك</h2>
                    <p class="text-gray-600">أدخل اسمك الكامل</p>

                    <div class="mt-1">
                        <input name="name" type="text" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="الاسم الكامل">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                            class="mt-4 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            تسجيل
                        </button>
                    </div>
                </div>
            @endif
            @endisset
        </div>
    </div>
</div>

