@props(['images' => collect()])

@if($images->isNotEmpty())
    <div class="row g-4">
        @foreach($images as $image)
            @if(isset($image->path) && $image->path)
                <div class="col-md-4">
                    <div class="gallery-item">
                        <img src="{{ asset($image->path) }}" 
                             class="img-fluid rounded shadow" 
                             alt="{{ $image->alt ?? $image->title ?? '' }}"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'; this.onerror=null;">
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif
