<div>

    <div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

     @if ($isMainFacility)
        <form wire:submit.prevent="{{ $selectedRoleId ? 'updateRole' : 'addPrimaryRole' }}">
            <div>
                <label for="isPrimary">{{ __('Is Primary') }}:</label>
                <input type="checkbox" id="isPrimary" wire:model="isPrimary" disabled checked>
            </div>

            <div>
                <label for="isPaid">{{ __('Is Paid') }}:</label>
                <input type="checkbox" id="isPaid" wire:model="isPaid">
            </div>

            <div>
                <label for="price">{{ __('Price') }}:</label>
                <input type="text" id="price" wire:model.defer="price">
            </div>

            <div>
                <label>{{ __('Translations') }}:</label>
                <div id="translations-container">
                    @foreach ($dynamicLocales as $locale)
                        <div class="translation-group" style="margin-bottom: 10px; border: 1px solid #eee; padding: 10px; border-radius: 5px;">
                            <label for="name_{{ $locale }}">{{ __('Role Name') }} ({{ strtoupper($locale) }}):</label>
                            <input type="text" id="name_{{ $locale }}" wire:model="translations.{{ $locale }}.name">
                            <label for="desc_{{ $locale }}">{{ __('Description') }} ({{ strtoupper($locale) }}):</label>
                            <textarea id="desc_{{ $locale }}" wire:model="translations.{{ $locale }}.description"></textarea>
                            <button type="button" wire:click="removeLocale('{{ $locale }}')" style="color:red; margin-top:5px;">-</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" wire:click="addLocale" style="margin-top:10px;">+</button>
            </div>

             <div>
                <label>{{ __('Select Permissions') }}:</label>
                @foreach ($permissions as $permission)
                    <div>
                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}">
                        <label>{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>

            <div>
                <button type="submit">{{ $selectedRoleId ? __('Update Role') : __('Add Primary Role') }}</button>
            </div>
        </form>
    @endif

     <form wire:submit.prevent="addSubRole">
        <div>
            <label>{{ __('Translations') }}:</label>
            <div id="sub-translations-container">
                @foreach ($dynamicLocales as $locale)
                    <div class="translation-group" style="margin-bottom: 10px; border: 1px solid #eee; padding: 10px; border-radius: 5px;">
                        <label for="subRoleName_{{ $locale }}">{{ __('Sub Role Name') }} ({{ strtoupper($locale) }}):</label>
                        <input type="text" id="subRoleName_{{ $locale }}" wire:model="translations.{{ $locale }}.name">
                        <label for="subRoleDesc_{{ $locale }}">{{ __('Description') }} ({{ strtoupper($locale) }}):</label>
                        <textarea id="subRoleDesc_{{ $locale }}" wire:model="translations.{{ $locale }}.description"></textarea>
                        <button type="button" wire:click="removeLocale('{{ $locale }}')" style="color:red; margin-top:5px;">-</button>
                    </div>
                @endforeach
            </div>
            <button type="button" wire:click="addLocale" style="margin-top:10px;">+</button>
        </div>

         <div>
            <label>{{ __('Select Permissions') }}:</label>
            @foreach ($permissions as $permission)
                <div>
                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}">
                    <label>{{ $permission->name }}</label>
                </div>
            @endforeach
        </div>

        <div>
            <button type="submit">{{ __('Add Sub Role') }}</button>
        </div>
    </form>

     <div class="mb-3">
        <input type="text" wire:model.debounce.500ms="roleSearch" placeholder="{{ __('بحث عن دور...') }}" class="form-control" style="max-width:300px; display:inline-block; margin-bottom:10px;">
    </div>

     <table>
        <thead>
            <tr>
                <th>{{ __('Role Name') }}</th>
                @foreach (config('app.locales') as $locale)
                    <th>{{ __('Description') }} ({{ strtoupper($locale) }})</th>
                @endforeach
                <th>{{ __('Permissions') }}</th>
                <th>{{ __('Users Count') }}</th>
                @if ($isMainFacility)
                    <th>{{ __('Type (Primary/Sub)') }}</th>
                    <th>{{ __('Is Paid') }}</th>
                    <th>{{ __('Price') }}</th>
                @endif
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                @if (empty($roleSearch) || str_contains(strtolower($role->name), strtolower($roleSearch)))
                <tr wire:key="role-{{ $role->id }}">
                    <td>{{ $role->name }}</td>
                    @foreach (config('app.locales') as $locale)
                        <td>{{ $role->translations[$locale]['description'] ?? '-' }}</td>
                    @endforeach
                    <td>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach ($role->permissions as $perm)
                                <li style="display: inline-block; background: #f1f1f1; border-radius: 5px; margin: 2px; padding: 2px 6px; font-size: 12px;">{{ $perm->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ method_exists($role, 'users') ? $role->users()->count() : '-' }}</td>
                    @if ($isMainFacility)
                        <td>{{ $role->is_primary ? __('Primary') : __('Sub') }}</td>
                        <td>{{ $role->is_paid ? __('Yes') : __('No') }}</td>
                        <td>{{ $role->price ? '$' . $role->price : '-' }}</td>
                    @endif
                    <td>
                        <button wire:click="editRole({{ $role->id }})">Edit</button>
                        <button wire:click="deleteRole({{ $role->id }})">Delete</button>
                        <button wire:click="copyRole({{ $role->id }})">Copy</button>
                        <button wire:click="suggestTranslations({{ $role->id }})">اقتراح ترجمة</button>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
