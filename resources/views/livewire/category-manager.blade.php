<div>
    <form wire:submit.prevent="{{ $editMode ? 'updateCategory' : 'storeCategory' }}">
        <div class="mb-3">
            <label for="isSubcategory" class="form-label">هل هي فئة فرعية؟</label>
            <input type="checkbox" id="isSubcategory" wire:model="isSubcategory" wire:change="updateSubcategory">
        </div>

        @if ($isSubcategory)
            <div class="mb-3">
                <label for="parent_id" class="form-label">الفئة الرئيسية:</label>
                <select id="parent_id" wire:model="parent_id" class="form-select">
                    <option value="">اختر الفئة الرئيسية</option>
                    @foreach ($categories as $categoryOption)
                        @if (!$category_id || $categoryOption->id != $category_id)
                            <option value="{{ $categoryOption->id }}">{{ $categoryOption->getTranslation('name', app()->getLocale()) }}</option>
                        @endif
                    @endforeach
                </select>
                @error('parent_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        @endif

        <x-translatable-field 
            name="name" 
            label="اسم الفئة"
            :languages="$languages"
            :translations="$translations['name'] ?? []"
            required
        />

        <x-translatable-field 
            name="description" 
            label="وصف الفئة"
            type="textarea"
            :languages="$languages"
            :translations="$translations['description'] ?? []"
        />

        <div class="mb-3">
            <label for="image" class="form-label">الصورة:</label>
            <input type="file" id="image" wire:model="tempImage" class="form-control">
            @error('tempImage') <span class="text-danger">{{ $message }}</span> @enderror
            @if ($tempImage)
                <img src="{{ $tempImage->temporaryUrl() }}" class="mt-2" width="100">
            @elseif($editMode && $category && $category->image)
                <img src="{{ Storage::url($category->image) }}" class="mt-2" width="100">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $editMode ? 'تحديث الفئة' : 'حفظ الفئة' }}
        </button>
    </form>

    <hr class="my-4">

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">قائمة الفئات</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الفئة الرئيسية</th>
                            <th>الصورة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->getTranslation('name', app()->getLocale()) }}</td>
                                <td>
                                    @if($category->parent)
                                        {{ $category->parent->getTranslation('name', app()->getLocale()) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($category->image)
                                        <img src="{{ Storage::url($category->image) }}" width="50" class="img-thumbnail">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" wire:click="editCategory({{ $category->id }})">
                                        <i class="fas fa-edit"></i> تعديل
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteCategory({{ $category->id }})"
                                            onclick="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
