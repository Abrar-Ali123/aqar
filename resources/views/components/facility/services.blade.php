@props(['services' => collect()])

@if($services->isNotEmpty())
    <div class="services-grid row g-4">
        @foreach($services as $service)
            <div class="col-md-6 col-lg-4">
                <div class="service-card h-100 p-4 bg-white rounded shadow-sm">
                    @php
                        $allowedIconPrefixes = ['fas ', 'far ', 'fab ', 'fa-'];
                        $hasValidIcon = $service->icon && Str::contains($service->icon, $allowedIconPrefixes);
                    @endphp

                    @if($hasValidIcon)
                        <div class="service-icon mb-3">
                            <i class="{{ $service->icon }} fa-2x text-primary"></i>
                        </div>
                    @endif
                    
                    <h5 class="service-title mb-3">
                        {{ optional($service->translate())->name ?? __('Unnamed Service') }}
                    </h5>
                    
                    @if(optional($service->translate())->description)
                        <p class="service-description mb-0 text-muted">
                            {{ optional($service->translate())->description }}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        {{ __('No services available.') }}
    </div>
@endif

<style>
    .service-card {
        transition: transform 0.2s ease-in-out;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--bs-light);
    }
</style>
