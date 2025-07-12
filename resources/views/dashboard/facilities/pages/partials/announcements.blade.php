@if($announcements && count($announcements))
    <div class="alert alert-warning rounded-0 text-center mb-0" style="font-size:1.1rem;">
        <marquee direction="right" scrollamount="6">
            @foreach($announcements as $a)
                <span class="mx-3">{{ $a['text'] }}</span>
            @endforeach
        </marquee>
    </div>
@endif
