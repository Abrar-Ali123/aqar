
<div class="container">
    <h1>قائمة المنشآت</h1>
    <ul>
        @foreach($facilities as $facility)
            <li>
                <a href="{{ route('facility.show', $facility->id) }}">{{ $facility->name }}</a>
            </li>
        @endforeach
    </ul>
</div>
