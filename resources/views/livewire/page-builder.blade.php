<div class="page-builder">
    <div class="flex h-screen bg-gray-100">
        <!-- شريط الأدوات الجانبي -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-4">
                <h2 class="text-lg font-bold mb-4">المكونات</h2>
                <div class="space-y-2">
                    @foreach($availableComponents as $component)
                        <div class="component-item p-2 bg-gray-50 rounded cursor-move hover:bg-gray-100"
                             draggable="true"
                             wire:key="component-{{ $component['type'] }}"
                             x-data
                             x-on:dragstart="
                                event.dataTransfer.setData('component', JSON.stringify($component));
                                event.target.classList.add('opacity-50');
                             "
                             x-on:dragend="event.target.classList.remove('opacity-50')">
                            <i class="{{ $component['icon'] }} mr-2"></i>
                            {{ $component['name'] }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- منطقة التحرير -->
        <div class="flex-1 p-8">
            <div class="bg-white rounded-lg shadow-lg min-h-full p-6"
                 x-data
                 x-on:dragover.prevent
                 x-on:drop.prevent="
                    const componentData = JSON.parse(event.dataTransfer.getData('component'));
                    $wire.handleComponentAdded(componentData);
                 ">
                
                @foreach($layout as $index => $component)
                    <div class="component-wrapper relative p-4 border border-dashed border-gray-300 mb-4 rounded"
                         wire:key="layout-{{ $component['id'] }}"
                         x-data="{ showControls: false }"
                         x-on:mouseenter="showControls = true"
                         x-on:mouseleave="showControls = false">
                        
                        <!-- أزرار التحكم -->
                        <div class="absolute top-2 right-2 space-x-2"
                             x-show="showControls"
                             x-transition>
                            <button class="text-blue-500 hover:text-blue-700"
                                    wire:click="$set('activeComponent', '{{ $component['id'] }}')">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button class="text-red-500 hover:text-red-700"
                                    wire:click="handleComponentRemoved('{{ $component['id'] }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <!-- المكون -->
                        <div class="component-content"
                             style="{{ collect($component['styles'])->map(fn($value, $prop) => "$prop: $value")->implode(';') }}">
                            @include('components.builder.' . $component['type'], [
                                'content' => $component['content'],
                                'settings' => $component['settings']
                            ])
                        </div>
                    </div>
                @endforeach

                @if(empty($layout))
                    <div class="text-center text-gray-500 py-12">
                        اسحب وأفلت المكونات هنا
                    </div>
                @endif
            </div>
        </div>

        <!-- لوحة الإعدادات -->
        @if($activeComponent)
            <div class="w-80 bg-white shadow-lg p-4">
                <h2 class="text-lg font-bold mb-4">إعدادات المكون</h2>
                @php
                    $component = collect($layout)->firstWhere('id', $activeComponent);
                @endphp
                @if($component)
                    @include('components.builder.settings.' . $component['type'], [
                        'settings' => $component['settings'],
                        'styles' => $component['styles'],
                        'componentId' => $activeComponent
                    ])
                @endif
            </div>
        @endif
    </div>
</div>
