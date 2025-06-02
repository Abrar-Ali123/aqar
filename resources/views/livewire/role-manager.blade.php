<div>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">إدارة الأدوار</h2>
            <div class="flex items-center">
                <input type="text" wire:model="search" placeholder="بحث..." class="rounded-lg border-gray-300 shadow-sm">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنشأة</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستوى</th>
                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $role->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $role->facility->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $role->level }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="showAudit({{ $role->id }})" class="text-indigo-600 hover:text-indigo-900 ml-4">سجل التدقيق</button>
                            <button wire:click="showAddPermission({{ $role->id }})" class="text-green-600 hover:text-green-900">إضافة صلاحية مؤقتة</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- سجل التدقيق -->
    <x-modal wire:model="showAuditModal">
        <div class="p-6">
            <h3 class="text-lg font-medium mb-4">سجل التدقيق - {{ $selectedRole->name ?? '' }}</h3>
            <div class="space-y-4">
                @if($selectedRole)
                    @foreach($selectedRole->audits()->latest()->get() as $audit)
                    <div class="border-r-4 border-indigo-500 p-4 bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $audit->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $audit->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($audit->action === 'created') bg-green-100 text-green-800
                                @elseif($audit->action === 'updated') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $audit->action }}
                            </span>
                        </div>
                        @if($audit->changes)
                        <div class="mt-2 text-sm">
                            <p class="font-medium">التغييرات:</p>
                            <ul class="list-disc list-inside space-y-1 mt-1">
                                @foreach($audit->changes as $field => $change)
                                <li>{{ $field }}: {{ is_array($change) ? json_encode($change) : $change }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <p class="text-sm text-gray-500 mt-2">IP: {{ $audit->ip_address }}</p>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </x-modal>

    <!-- إضافة صلاحية مؤقتة -->
    <x-modal wire:model="showPermissionModal">
        <div class="p-6">
            <h3 class="text-lg font-medium mb-4">إضافة صلاحية مؤقتة - {{ $selectedRole->name ?? '' }}</h3>
            <form wire:submit.prevent="addTemporaryPermission" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">الصلاحية</label>
                    <select wire:model="selectedPermission" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">اختر صلاحية</option>
                        @foreach($permissions as $permission)
                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedPermission') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">تاريخ الانتهاء</label>
                    <input type="datetime-local" wire:model="expiresAt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('expiresAt') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">السبب</label>
                    <textarea wire:model="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="$set('showPermissionModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                        إضافة
                    </button>
                </div>
            </form>

            @if($selectedRole)
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">الصلاحيات المؤقتة الحالية</h4>
                <div class="space-y-2">
                    @foreach($selectedRole->temporaryPermissions as $temp)
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <div>
                            <p class="font-medium">{{ $temp->permission->name }}</p>
                            <p class="text-sm text-gray-500">تنتهي في: {{ $temp->expires_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <button wire:click="revokeTemporaryPermission({{ $temp->id }})" class="text-red-600 hover:text-red-900">
                            إلغاء
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </x-modal>
</div>
