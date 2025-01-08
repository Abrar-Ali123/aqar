<div class="container">
    <h2 class="fw-bold text-primary">إضافة بنك جديد</h2>
    <form action="{{ route('banks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="logo" class="form-label">شعار البنك</label>
            <input type="file" class="form-control" id="logo" name="logo" accept="image/*" required>
        </div>

        <h5 class="mt-4">ترجمات الاسم</h5>
        <div id="translations">
            @foreach (config('app.locales') as $locale)
                <div class="mb-6">
                    <label for="name_{{ $locale }}" class="block text-sm font-medium text-gray-700">اسم البنك ({{ strtoupper($locale) }}):</label>
                    <input type="text" class="form-control mt-1 block w-full p-2 border border-gray-300 rounded-md" id="name_{{ $locale }}" name="translations[{{ $locale }}][name]" required>
                    <input type="hidden" name="translations[{{ $locale }}][locale]" value="{{ $locale }}">
                    @error('translations.' . $locale . '.name') <span class="error">{{ $message }}</span> @enderror
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">إضافة البنك</button>
    </form>
</div>
