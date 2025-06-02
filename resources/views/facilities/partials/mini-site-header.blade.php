@use('Illuminate\Support\Facades\Storage')

<div class="facility-header position-relative mb-4" style="background: linear-gradient(120deg, {{ $facility->primary_color ?? '#0052cc' }} 0%, #fff 100%); min-height: 260px;">
    <img src="{{ $facility->cover ? Storage::url($facility->cover) : asset('images/facility-default-cover.jpg') }}" alt="cover" class="facility-cover w-100 rounded-bottom" style="object-fit:cover; min-height:180px; max-height:260px; opacity:0.25;">
    <div class="facility-header-content position-absolute top-0 start-0 w-100 h-100 d-flex flex-column flex-md-row align-items-end align-items-md-center justify-content-between px-4 pb-3" style="z-index:2;">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $facility->logo ? Storage::url($facility->logo) : asset('images/facility-default-logo.png') }}" alt="logo" class="rounded-circle shadow" style="width:100px; height:100px; object-fit:cover; border:4px solid #fff;">
            <div>
                <h1 class="fw-bold mb-1" style="color:{{ $facility->primary_color ?? '#0052cc' }}">{{ $facility->translations->first()->name }}</h1>
                <div class="text-muted">{{ $facility->translations->first()->slogan }}</div>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-2">
            @if($facility->email)
                <a href="mailto:{{ $facility->email }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-envelope me-1"></i> {{ $facility->email }}</a>
            @endif
            @if($facility->phone)
                <a href="tel:{{ $facility->phone }}" class="btn btn-outline-success btn-sm"><i class="fas fa-phone me-1"></i> {{ $facility->phone }}</a>
            @endif
            @if($facility->website)
                <a href="{{ $facility->website }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="fas fa-globe me-1"></i> {{ $facility->website }}</a>
            @endif
        </div>
    </div>
</div>
