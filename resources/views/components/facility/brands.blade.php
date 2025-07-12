@props(['brands'])

<div class="row g-4 justify-content-center">
    @foreach($brands as $brand)
        <div class="col-6 col-md-3">
            <div class="brand-card text-center">
                @if($brand->logo)
                    <img src="{{ asset($brand->logo) }}" 
                         alt="{{ $brand->translate()->name }}" 
                         class="img-fluid mb-2"
                         loading="lazy">
                @endif
                <h5 class="brand-name">{{ $brand->translate()->name }}</h5>
            </div>
        </div>
    @endforeach
</div>
