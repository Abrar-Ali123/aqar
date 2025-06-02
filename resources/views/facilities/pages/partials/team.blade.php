<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('فريق العمل')</h2>
    <div class="row g-4 justify-content-center">
        @forelse($team as $member)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm h-100 text-center">
                    <img src="{{ Storage::url($member['photo'] ?? '/images/default-avatar.png') }}" class="card-img-top mx-auto mt-3 rounded-circle" style="width:100px;height:100px;object-fit:cover;" alt="{{ $member['name'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $member['name'] }}</h5>
                        <p class="card-text text-muted mb-1">{{ $member['position'] }}</p>
                        @if(!empty($member['bio']))
                            <p class="card-text small">{{ $member['bio'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">@lang('لا يوجد أعضاء فريق بعد.')</div>
        @endforelse
    </div>
</section>
