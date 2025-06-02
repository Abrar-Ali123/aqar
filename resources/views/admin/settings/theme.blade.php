@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6>{{ __('admin.theme_settings') }}</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-paint-brush text-info" aria-hidden="true"></i>
                                {{ __('admin.theme_settings_desc') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4">
                    <form action="{{ route('admin.settings.theme.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- اختيار الثيم الافتراضي -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">{{ __('admin.default_theme') }}</label>
                                <div class="row g-4">
                                    <!-- الثيم العقاري -->
                                    <div class="col-md-4">
                                        <div class="theme-option card cursor-pointer @if($settings->default_theme === 'real_estate') border-primary @endif">
                                            <div class="position-relative">
                                                <img src="{{ asset('admin/img/themes/real-estate.jpg') }}" 
                                                     class="card-img-top" 
                                                     alt="Real Estate Theme">
                                                @if($settings->default_theme === 'real_estate')
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="radio" 
                                                           name="default_theme" 
                                                           id="theme_real_estate" 
                                                           value="real_estate"
                                                           @checked($settings->default_theme === 'real_estate')>
                                                    <label class="form-check-label" for="theme_real_estate">
                                                        <h6 class="mb-1">{{ __('admin.real_estate_theme') }}</h6>
                                                        <p class="text-sm text-muted mb-0">{{ __('admin.real_estate_theme_desc') }}</p>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ثيم السوق -->
                                    <div class="col-md-4">
                                        <div class="theme-option card cursor-pointer @if($settings->default_theme === 'marketplace') border-primary @endif">
                                            <div class="position-relative">
                                                <img src="{{ asset('admin/img/themes/marketplace.jpg') }}" 
                                                     class="card-img-top" 
                                                     alt="Marketplace Theme">
                                                @if($settings->default_theme === 'marketplace')
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="radio" 
                                                           name="default_theme" 
                                                           id="theme_marketplace" 
                                                           value="marketplace"
                                                           @checked($settings->default_theme === 'marketplace')>
                                                    <label class="form-check-label" for="theme_marketplace">
                                                        <h6 class="mb-1">{{ __('admin.marketplace_theme') }}</h6>
                                                        <p class="text-sm text-muted mb-0">{{ __('admin.marketplace_theme_desc') }}</p>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- الثيم المجتمعي -->
                                    <div class="col-md-4">
                                        <div class="theme-option card cursor-pointer @if($settings->default_theme === 'community') border-primary @endif">
                                            <div class="position-relative">
                                                <img src="{{ asset('admin/img/themes/community.jpg') }}" 
                                                     class="card-img-top" 
                                                     alt="Community Theme">
                                                @if($settings->default_theme === 'community')
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="radio" 
                                                           name="default_theme" 
                                                           id="theme_community" 
                                                           value="community"
                                                           @checked($settings->default_theme === 'community')>
                                                    <label class="form-check-label" for="theme_community">
                                                        <h6 class="mb-1">{{ __('admin.community_theme') }}</h6>
                                                        <p class="text-sm text-muted mb-0">{{ __('admin.community_theme_desc') }}</p>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- إعدادات الثيمات -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">{{ __('admin.theme_features') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="features[theme_switcher]" 
                                                   id="theme_switcher"
                                                   @checked($settings->features['theme_switcher'] ?? false)>
                                            <label class="form-check-label" for="theme_switcher">
                                                {{ __('admin.enable_theme_switcher') }}
                                            </label>
                                            <small class="d-block text-muted">
                                                {{ __('admin.enable_theme_switcher_desc') }}
                                            </small>
                                        </div>

                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="features[auto_theme]" 
                                                   id="auto_theme"
                                                   @checked($settings->features['auto_theme'] ?? false)>
                                            <label class="form-check-label" for="auto_theme">
                                                {{ __('admin.enable_auto_theme') }}
                                            </label>
                                            <small class="d-block text-muted">
                                                {{ __('admin.enable_auto_theme_desc') }}
                                            </small>
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="features[theme_preview]" 
                                                   id="theme_preview"
                                                   @checked($settings->features['theme_preview'] ?? false)>
                                            <label class="form-check-label" for="theme_preview">
                                                {{ __('admin.enable_theme_preview') }}
                                            </label>
                                            <small class="d-block text-muted">
                                                {{ __('admin.enable_theme_preview_desc') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">{{ __('admin.theme_customization') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('admin.primary_color') }}</label>
                                            <input type="color" 
                                                   class="form-control form-control-color w-100" 
                                                   name="customization[primary_color]"
                                                   value="{{ $settings->customization['primary_color'] ?? '#0d6efd' }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{{ __('admin.font_family') }}</label>
                                            <select class="form-select" name="customization[font_family]">
                                                <option value="cairo" @selected(($settings->customization['font_family'] ?? '') === 'cairo')>Cairo</option>
                                                <option value="tajawal" @selected(($settings->customization['font_family'] ?? '') === 'tajawal')>Tajawal</option>
                                                <option value="almarai" @selected(($settings->customization['font_family'] ?? '') === 'almarai')>Almarai</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{{ __('admin.border_radius') }}</label>
                                            <select class="form-select" name="customization[border_radius]">
                                                <option value="0" @selected(($settings->customization['border_radius'] ?? '') === '0')>{{ __('admin.sharp') }}</option>
                                                <option value="0.25rem" @selected(($settings->customization['border_radius'] ?? '') === '0.25rem')>{{ __('admin.slightly_rounded') }}</option>
                                                <option value="0.5rem" @selected(($settings->customization['border_radius'] ?? '') === '0.5rem')>{{ __('admin.rounded') }}</option>
                                                <option value="1rem" @selected(($settings->customization['border_radius'] ?? '') === '1rem')>{{ __('admin.fully_rounded') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('admin.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.theme-option {
    transition: all 0.3s ease;
}

.theme-option:hover {
    transform: translateY(-5px);
}

.theme-option .card-img-top {
    height: 200px;
    object-fit: cover;
}

.cursor-pointer {
    cursor: pointer;
}

/* RTL Support */
[dir="rtl"] .me-2 {
    margin-right: 0 !important;
    margin-left: 0.5rem !important;
}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.theme-option').forEach(option => {
    option.addEventListener('click', function() {
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;
        
        // Remove border from all options
        document.querySelectorAll('.theme-option').forEach(opt => {
            opt.classList.remove('border-primary');
        });
        
        // Add border to selected option
        this.classList.add('border-primary');
    });
});
</script>
@endpush
