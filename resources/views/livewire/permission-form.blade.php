<div>

<div>
<form wire:submit.prevent="addPermission">
    <label>{{ __('Translations') }}:</label>
    <div id="translations-container">
        @foreach ($dynamicLocales as $locale)
            <div class="translation-group" style="margin-bottom: 10px; border: 1px solid #eee; padding: 10px; border-radius: 5px;">
                <label for="name_{{ $locale }}">{{ strtoupper($locale) }} {{ __('Name') }}</label>
                <input type="text" class="form-control" id="name_{{ $locale }}" wire:model.live="translations.{{ $locale }}.name" placeholder="Enter permission name in {{ $locale }}">
                <label for="desc_{{ $locale }}">{{ __('Description') }} ({{ strtoupper($locale) }}):</label>
                <textarea id="desc_{{ $locale }}" class="form-control" wire:model="translations.{{ $locale }}.description"></textarea>
                <button type="button" wire:click="removeLocale('{{ $locale }}')" style="color:red; margin-top:5px;">-</button>
                @error('translations.' . $locale . '.name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        @endforeach
    </div>
    <button type="button" wire:click="addLocale" style="margin-top:10px;">+</button>
    <div class="mb-3">
        <label class="form-label">Allowed Pages</label>
        @foreach($pages as $index => $page)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="page_{{ $index }}" wire:model="pages.{{ $index }}.is_allowed">
                <label class="form-check-label" for="page_{{ $index }}">{{ $page['name'] }}</label>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-4">
            <label>{{ __('مستوى المنشأة') }}</label>
            <input type="text" wire:model="facilityLevel" class="form-control" placeholder="مثال: فرع/إدارة/مؤسسة">
        </div>
        <div class="col-md-4">
            <label>{{ __('تاريخ البداية') }}</label>
            <input type="date" wire:model="validFrom" class="form-control">
        </div>
        <div class="col-md-4">
            <label>{{ __('تاريخ النهاية') }}</label>
            <input type="date" wire:model="validTo" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
</div>

<div class="mb-3">
    <input type="text" wire:model.debounce.500ms="permissionSearch" placeholder="{{ __('بحث عن صلاحية...') }}" class="form-control" style="max-width:300px; display:inline-block; margin-bottom:10px;">
</div>

<div class="mb-3">
    <button type="button" wire:click="$set('viewMode', viewMode === 'table' ? 'tree' : 'table')" class="btn btn-secondary mb-2">
        {{ viewMode === 'tree' ? __('عرض كجدول') : __('عرض كشجرة') }}
    </button>
</div>

@if($viewMode === 'tree')
    <div class="permission-tree">
        @foreach ($categories as $category)
            @include('livewire.partials.permission-category-tree', ['category' => $category])
        @endforeach
    </div>
@else
    <!-- جدول الصلاحيات -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th wire:click="sortBy('name')" style="cursor:pointer;">{{ __('Permission Name') }}</th>
                @foreach (config('app.locales') as $locale)
                    <th>{{ __('Description') }} ({{ strtoupper($locale) }})</th>
                @endforeach
                <th>{{ __('Allowed Pages') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                @if (empty($permissionSearch) || str_contains(strtolower($permission->name), strtolower($permissionSearch)))
                <tr>
                    <td>{{ $permission->name }}</td>
                    @foreach (config('app.locales') as $locale)
                        <td>{{ $permission->translations[$locale]['description'] ?? '-' }}</td>
                    @endforeach
                    <td>
                        @php $pages = json_decode($permission->pages, true); @endphp
                        @if ($pages)
                            <ul style="list-style:none; padding:0; margin:0;">
                                @foreach ($pages as $page)
                                    <li style="display:inline-block; background:#f9f9f9; border-radius:4px; margin:2px; padding:2px 6px; font-size:12px;">{{ $page }}</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <button wire:click="edit({{ $permission->id }})">Edit</button>
                        <button wire:click="delete({{ $permission->id }})">Delete</button>
                        <button wire:click="copyPermission({{ $permission->id }})">Copy</button>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endif

  <button id="toggleView">تبديل العرض</button>




<script>
    document.getElementById('toggleView').addEventListener('click', function() {
   var tables = document.querySelectorAll('table');

   tables.forEach(function(table) {
    table.classList.toggle('grid-view');
  });
});

    </script>
</div>
