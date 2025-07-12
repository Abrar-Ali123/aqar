@props(['rating' => 0])

@php
    $rating = is_numeric($rating) ? (float) $rating : 0;
@endphp

<div class="flex items-center justify-center gap-1">
    @for($i = 1; $i <= 5; $i++)
        @if($i <= $rating)
            <x-heroicon-s-star class="w-6 h-6 text-yellow-400" />
        @else
            <x-heroicon-s-star class="w-6 h-6 text-gray-400" />
        @endif
    @endfor
    <span class="text-lg font-medium">({{ number_format($rating, 1) }})</span>
</div>
