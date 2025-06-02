<div>
    <h1>ميزات</h1>
    <table class="table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>الرمز</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($features as $feature)
                <tr>
                    <td>
                        @if ($selectedFeatureId === $feature->id)
                            @foreach (config('app.locales') as $locale)
                                <div>{{ $locale }}:
                                    <input type="text" wire:model.defer="names.{{ $locale }}" class="form-control">
                                </div>
                            @endforeach
                        @else
                            <span wire:click="selectFeature({{ $feature->id }})">{{ $feature->translations->firstWhere('locale', app()->getLocale())->name ?? $feature->name }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($selectedFeatureId === $feature->id)
                            <input type="file" wire:model.defer="icon" class="form-control-file">
                            @if ($icon)
                                <img src="{{ $icon->temporaryUrl() }}" alt="رمز الميزة" width="50">
                            @else
                            <img src="{{ asset('storage/'.$feature->icon) }}" width="20" alt="{{ __('building.Building Image') }}">
                            @endif
                        @else
        <img src="{{ asset('storage/'.$feature->icon) }}" width="20" alt="{{ __('building.Building Image') }}">

                        @endif
                    </td>
                    <td>
                        <button wire:click="selectFeature({{ $feature->id }})" class="btn btn-primary">
                            {{ $selectedFeatureId === $feature->id ? 'إلغاء' : 'تحرير' }}
                        </button>
                        @if ($selectedFeatureId === $feature->id)
                            <button wire:click="updateFeature" class="btn btn-success">حفظ</button>
                        @endif
                        <button wire:click="deleteFeature({{ $feature->id }})" class="btn btn-danger">حذف</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form wire:submit.prevent="addFeature">
        <div class="mb-3">
            <x-translatable-input label="الميزة" field="newNames" required="true" help="أدخل اسم الميزة بكل لغة" />
        </div>
        <div class="mb-3">
            <label for="newIcon" class="form-label">الرمز</label>
            <input type="file" class="form-control-file" id="newIcon" wire:model="newIcon">
            @error('newIcon') <span class="error">{{ $message }}</span> @enderror
            @if ($newIcon)
                <img src="{{ $newIcon->temporaryUrl() }}" alt="رمز جديد" width="50">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">حفظ</button>
    </form>
</div>
