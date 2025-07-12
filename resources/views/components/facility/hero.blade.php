@props(['facility'])

<section class="hero-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 mb-3">{{ optional($facility->translate())->name ?? $facility->name }}</h1>
                <p class="lead mb-4">{{ optional($facility->translate())->description ?? '' }}</p>
                
                @if($facility->businessSector || $facility->businessCategory)
                    <div class="mb-4">
                        @if($facility->businessSector)
                            <span class="badge bg-primary me-2">
                                {{ optional($facility->businessSector->translate())->name ?? $facility->businessSector->name }}
                            </span>
                        @endif
                        @if($facility->businessCategory)
                            <span class="badge bg-secondary">
                                {{ optional($facility->businessCategory->translate())->name ?? $facility->businessCategory->name }}
                            </span>
                        @endif
                    </div>
                @endif

                @if($facility->address)
                    <p class="mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $facility->address }}
                    </p>
                @endif

                @if($facility->phone)
                    <p class="mb-3">
                        <i class="fas fa-phone me-2"></i>
                        <a href="tel:{{ $facility->phone }}" class="text-decoration-none">
                            {{ $facility->phone }}
                        </a>
                    </p>
                @endif

                @if($facility->email)
                    <p class="mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:{{ $facility->email }}" class="text-decoration-none">
                            {{ $facility->email }}
                        </a>
                    </p>
                @endif
            </div>
            <div class="col-lg-6">
                @if($facility->header)
                    <img src="{{ asset($facility->header) }}" 
                         alt="{{ optional($facility->translate())->name ?? $facility->name }}" 
                         class="img-fluid rounded shadow-lg">
                @endif
            </div>
        </div>
    </div>
</section>
