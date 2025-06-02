<div class="section-manager">
    <h5 class="mb-3">@lang('ترتيب الأقسام')</h5>
    <div class="section-list" wire:sortable="updateOrder" wire:sortable.options="{ animation: 150 }">
        @foreach($sections as $section)
        <div wire:key="section-{{ $section }}" 
             wire:sortable.item="{{ $section }}" 
             class="section-item {{ $meta[$section]['status'] }}">
            
            <div wire:sortable.handle class="section-handle">
                <i class="fas fa-grip-vertical"></i>
            </div>

            <div class="section-icon">
                <i class="fas {{ $meta[$section]['icon'] }}"></i>
            </div>

            <div class="section-info">
                <h6 class="section-title">{{ $meta[$section]['title'] }}</h6>
                <span class="section-status">
                    @if($meta[$section]['status'] === 'complete')
                        <i class="fas fa-check-circle text-success"></i> مكتمل
                    @elseif($meta[$section]['status'] === 'incomplete')
                        <i class="fas fa-exclamation-circle text-warning"></i> غير مكتمل
                    @else
                        <i class="fas fa-times-circle text-muted"></i> غير مفعل
                    @endif
                </span>
            </div>

            <div class="section-actions">
                <button type="button" class="btn btn-sm btn-outline-primary" 
                    wire:click="$emit('editSection', '{{ $section }}')">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
        </div>
        @endforeach
    </ul>
</div>
