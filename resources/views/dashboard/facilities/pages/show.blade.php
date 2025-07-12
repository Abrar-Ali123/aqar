@extends('facilities.pages.layouts.modern')

@section('content')
    @include('facilities.pages.partials.hero')
    <div class="container py-3">
        <div class="mb-4">
            <h1 class="fw-bold mb-2">{{ $page->title }}</h1>
            <div class="text-muted mb-3">{{ $facility->name }}</div>
        </div>
        <div class="row g-4">
            @foreach($page->attributeValues as $attributeValue)
                @php $attribute = $attributeValue->attribute; @endphp
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $attribute->translations[app()->getLocale()]['name'] ?? $attribute->key }}</h5>
                            <div class="card-text">
                                @if($attribute->type === 'image')
                                    <img src="{{ Storage::url($attributeValue->value) }}" class="img-fluid rounded" alt="{{ $attribute->key }}">
                                @else
                                    {{ $attributeValue->value }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @includeWhen(isset($page->gallery) && count($page->gallery), 'facilities.pages.partials.gallery', ['gallery' => $page->gallery])
        @includeWhen(isset($page->team) && count($page->team), 'facilities.pages.partials.team', ['team' => $page->team])
        @includeWhen(isset($page->services) && count($page->services), 'facilities.pages.partials.services', ['services' => $page->services])
        @includeWhen(isset($page->faq) && count($page->faq), 'facilities.pages.partials.faq', ['faq' => $page->faq])
        @includeWhen(isset($page->offers) && count($page->offers), 'facilities.pages.partials.offers', ['offers' => $page->offers])
        @includeWhen(isset($page->announcements) && count($page->announcements), 'facilities.pages.partials.announcements', ['announcements' => $page->announcements])
        @includeWhen(isset($page->partners) && count($page->partners), 'facilities.pages.partials.partners', ['partners' => $page->partners])
        @includeWhen(isset($page->social) && count($page->social), 'facilities.pages.partials.social', ['social' => $page->social])
        @includeWhen(isset($page->testimonials) && count($page->testimonials), 'facilities.pages.partials.testimonials-slider', ['testimonials' => $page->testimonials])
        @includeWhen(isset($page->blog) && count($page->blog), 'facilities.pages.partials.blog', ['blog' => $page->blog])
        @includeWhen(isset($page->enable_booking) && $page->enable_booking, 'facilities.pages.partials.booking-form')
        @includeWhen(isset($facility->latitude) && isset($facility->longitude), 'facilities.pages.partials.map')
        @if($page->enable_contact_form)
            @include('facilities.pages.partials.contact-form')
        @endif
        @if($page->enable_reviews)
            @include('facilities.pages.partials.reviews')
        @endif
    </div>
@endsection

@if($page->meta_title)
    @section('title', $page->meta_title)
@endif
@if($page->meta_description)
    @section('meta_description', $page->meta_description)
@endif
@if($page->meta_image)
    @section('meta_image', $page->meta_image)
@endif
@if($page->analytics_code)
    @push('scripts')
        {!! $page->analytics_code !!}
    @endpush
@endif
@if($page->facebook_pixel)
    @push('scripts')
        {!! $page->facebook_pixel !!}
    @endpush
@endif
@push('scripts')
<script>
    fetch("{{ route('facilities.pages.trackVisit', [$facility->id, $page->id]) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    });
</script>
@endpush
