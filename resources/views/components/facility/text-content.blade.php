@props(['data', 'content'])

<div class="text-content">
    @if(isset($data['title']))
        <h2 class="text-2xl font-bold mb-4">{{ $data['title'] }}</h2>
    @endif

    @if(isset($data['subtitle']))
        <h3 class="text-xl text-gray-600 mb-4">{{ $data['subtitle'] }}</h3>
    @endif

    <div class="prose max-w-none">
        {!! $content[$data['content_key']] ?? '' !!}
    </div>
</div>
