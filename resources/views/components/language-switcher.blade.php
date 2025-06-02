@php
    use Illuminate\Support\Facades\Route;
    use App\Models\Language;
    
    // Get current language with fallback
    $currentLanguage = Language::getCurrentLanguage() ?? Language::getDefaultLanguage();
    
    // If no language is available, create a default one
    if (!$currentLanguage) {
        $currentLanguage = new Language([
            'name' => 'English',
            'code' => 'en',
            'direction' => 'ltr',
            'is_active' => true,
            'is_default' => true
        ]);
        $currentLanguage->save();
    }
    
    $otherLanguages = Language::getOtherLanguages();
    
    // الحصول على المسار الحالي
    $currentRouteName = Route::currentRouteName();
    $currentParameters = Route::current()?->parameters() ?? [];
    unset($currentParameters['locale']); // إزالة معامل اللغة
@endphp

<div class="language-switcher d-flex align-items-center">
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('flags/' . $currentLanguage->code . '.svg') }}" alt="{{ $currentLanguage->name }}" class="flag-icon me-1">
            {{ $currentLanguage->name }}
        </button>
        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
            @foreach($otherLanguages as $language)
                <li>
                    <a href="{{ route($currentRouteName, array_merge(['locale' => $language->code], $currentParameters)) }}" class="dropdown-item">
                        <img src="{{ asset('flags/' . $language->code . '.svg') }}" alt="{{ $language->name }}" class="flag-icon me-1">
                        {{ $language->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<style>
.language-switcher .dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 120px;
}

.language-switcher .dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    padding: 0.5rem 1rem;
    cursor: pointer;
}

.language-switcher .dropdown-item:hover {
    background-color: var(--bs-dropdown-link-hover-bg);
}

.flag-icon {
    width: 1.2em;
    height: 1.2em;
    object-fit: cover;
    border-radius: 2px;
}

/* RTL Support */
[dir="rtl"] .language-switcher .dropdown-toggle::after {
    margin-right: 0.5rem;
    margin-left: 0;
}

[dir="rtl"] .language-switcher .dropdown-menu {
    text-align: right;
}

[dir="rtl"] .me-1 {
    margin-left: 0.25rem !important;
    margin-right: 0 !important;
}

[dir="rtl"] .language-switcher .dropdown-item {
    text-align: right;
}
</style>
