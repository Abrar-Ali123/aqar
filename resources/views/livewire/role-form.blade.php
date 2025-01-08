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

             @foreach (config('app.locales') as $locale)
                <div>
                    <label for="name_{{ $locale }}">{{ __('Role Name') }} ({{ strtoupper($locale) }}):</label>
                    <input type="text" id="name_{{ $locale }}" wire:model="translations.{{ $locale }}.name">
                </div>
            @endforeach

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
            @foreach (config('app.locales') as $locale)
                <div>
                    <label for="subRoleName_{{ $locale }}">{{ __('Sub Role Name') }} ({{ strtoupper($locale) }}):</label>
                    <input type="text" id="subRoleName_{{ $locale }}" wire:model="translations.{{ $locale }}.name">
                </div>
            @endforeach
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

     <table>
        <thead>
            <tr>
                <th>{{ __('Role Name') }}</th>
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
                <tr wire:key="role-{{ $role->id }}">
                    <td>{{ $role->name }}</td>

                    @if ($isMainFacility)
                        <td>{{ $role->is_primary ? __('Primary') : __('Sub') }}</td>
                        <td>{{ $role->is_paid ? __('Yes') : __('No') }}</td>
                        <td>{{ $role->price ? '$' . $role->price : '-' }}</td>
                    @endif

                    <td>
                        <button wire:click="editRole({{ $role->id }})">Edit</button>
                        <button wire:click="deleteRole({{ $role->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
