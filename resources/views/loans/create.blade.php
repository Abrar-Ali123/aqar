<div class="container">
    <h2 class="fw-bold text-primary">تقديم طلب قرض جديد</h2>
    <form action="{{ route('loans.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- حقل تاريخ الميلاد -->
        <div class="mb-3">
            <label for="birth" class="form-label">تاريخ الميلاد</label>
            <input type="date" class="form-control" id="birth" name="birth" required>
        </div>

        <!-- حقل الراتب -->
        <div class="mb-3">
            <label for="salary" class="form-label">الراتب</label>
            <input type="number" class="form-control" id="salary" name="salary" step="0.01" required>
        </div>

        <!-- حقل الالتزامات المالية -->
        <div class="mb-3">
            <label for="commitments" class="form-label">الالتزامات المالية</label>
            <input type="number" class="form-control" id="commitments" name="commitments" step="0.01">
        </div>

        <!-- حقل الخدمة العسكرية -->
        <div class="mb-3">
            <label for="military" class="form-label">الخدمة العسكرية</label>
            <select class="form-control" id="military" name="military" required>
                <option value="1">نعم</option>
                <option value="0">لا</option>
            </select>
        </div>

        <!-- حقل الرتبة العسكرية -->
        <div class="mb-3">
            <label for="rank" class="form-label">الرتبة العسكرية</label>
            <input type="text" class="form-control" id="rank" name="rank">
        </div>

        <!-- حقل تاريخ التوظيف -->
        <div class="mb-3">
            <label for="employment" class="form-label">تاريخ التوظيف</label>
            <input type="date" class="form-control" id="employment" name="employment">
        </div>


        <!-- اختيار البنك -->
        <div class="mb-3">
            <label for="bank_id" class="form-label">البنك</label>
            <select class="form-control" id="bank_id" name="bank_id" required>
                <!-- هنا يجب تمرير قائمة البنوك من الكنترولر -->
                @foreach($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- ترجمات الوكالة -->
        <h5 class="mt-4">ترجمات اسم الوكالة</h5>
        <div id="translations">
            @foreach (config('app.locales') as $locale)
                <div class="mb-3">
                    <label for="agency_{{ $locale }}" class="form-label">اسم الوكالة ({{ strtoupper($locale) }}):</label>
                    <input type="text" class="form-control" id="agency_{{ $locale }}" name="translations[{{ $locale }}][agency]" required>
                    <input type="hidden" name="translations[{{ $locale }}][locale]" value="{{ $locale }}">
                    @error('translations.' . $locale . '.agency') <span class="error">{{ $message }}</span> @enderror
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">تقديم الطلب</button>
    </form>
</div>
