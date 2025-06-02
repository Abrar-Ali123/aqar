@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'model' => null,
    'placeholder' => '',
    'hint' => '',
    'locales' => null
])

@php
    $locales = $locales ?? config('app.locales', ['ar', 'en']);
    $defaultLocale = config('app.fallback_locale', 'ar');
    $fieldId = str_replace(['[', ']', '.'], '_', $name);
@endphp

<div class="form-group translatable-field">
    <label>{{ $label }}</label>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" role="tablist" id="translatable-tabs-{{ $fieldId }}">
            @foreach($locales as $locale)
                <li class="nav-item" id="nav-item-{{ $fieldId }}-{{ $locale }}">
                    <a class="nav-link {{ $locale === $defaultLocale ? 'active' : '' }}"
                       id="tab-{{ $fieldId }}-{{ $locale }}"
                       data-toggle="tab"
                       href="#content-{{ $fieldId }}-{{ $locale }}"
                       role="tab"
                       aria-controls="content-{{ $fieldId }}-{{ $locale }}"
                       aria-selected="{{ $locale === $defaultLocale ? 'true' : 'false' }}">
                        {{ strtoupper($locale) }}
                        @if($locale === $defaultLocale)
                            <i class="fas fa-star text-warning"></i>
                        @endif
                    </a>
                    @if($locale !== $defaultLocale)
                        <button type="button" class="btn btn-link btn-sm text-danger p-0 ml-1 remove-lang-btn" title="حذف اللغة" data-locale="{{ $locale }}" data-fieldid="{{ $fieldId }}"><i class="fas fa-times"></i></button>
                    @endif
                </li>
            @endforeach
            <li class="nav-item">
                <button type="button" class="btn btn-link nav-link p-0" id="add-lang-btn-{{ $fieldId }}" title="إضافة لغة"><i class="fas fa-plus"></i></button>
            </li>
        </ul>

        <div class="tab-content" id="translatable-tabs-content-{{ $fieldId }}">
            @foreach($locales as $locale)
                <div class="tab-pane fade {{ $locale === $defaultLocale ? 'show active' : '' }}"
                     id="content-{{ $fieldId }}-{{ $locale }}"
                     role="tabpanel"
                     aria-labelledby="tab-{{ $fieldId }}-{{ $locale }}">
                    
                    @if($type === 'textarea')
                        <textarea
                            name="{{ $name }}[{{ $locale }}]"
                            id="{{ $fieldId }}_{{ $locale }}"
                            class="form-control @error("{$name}.{$locale}") is-invalid @enderror"
                            rows="3"
                            dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}"
                            {{ $required && $locale === $defaultLocale ? 'required' : '' }}
                            placeholder="{{ $placeholder }}"
                        >{{ old("{$name}.{$locale}", $model ? $model->getTranslation($name, $locale) : '') }}</textarea>
                    @elseif($type === 'editor')
                        <div class="editor-container">
                            <textarea
                                name="{{ $name }}[{{ $locale }}]"
                                id="{{ $fieldId }}_{{ $locale }}"
                                class="editor @error("{$name}.{$locale}") is-invalid @enderror"
                                dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}"
                                {{ $required && $locale === $defaultLocale ? 'required' : '' }}
                            >{{ old("{$name}.{$locale}", $model ? $model->getTranslation($name, $locale) : '') }}</textarea>
                        </div>
                    @elseif($type === 'image')
                        <div class="mb-2">
                            <input type="file" accept="image/*" name="{{ $name }}[{{ $locale }}]" id="{{ $fieldId }}_{{ $locale }}_image" class="form-control-file">
                            @if(isset($model) && $model->getTranslation($name, $locale))
                                <img src="{{ asset('storage/' . $model->getTranslation($name, $locale)) }}" alt="صورة {{ $label }} ({{ strtoupper($locale) }})" class="img-thumbnail mt-2" style="max-width: 120px;">
                            @endif
                        </div>
                    @else
                        <input
                            type="{{ $type }}"
                            name="{{ $name }}[{{ $locale }}]"
                            id="{{ $fieldId }}_{{ $locale }}"
                            class="form-control @error("{$name}.{$locale}") is-invalid @enderror"
                            value="{{ old("{$name}.{$locale}", $model ? $model->getTranslation($name, $locale) : '') }}"
                            dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}"
                            {{ $required && $locale === $defaultLocale ? 'required' : '' }}
                            placeholder="{{ $placeholder }}"
                        >
                    @endif

                    @error("{$name}.{$locale}")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
        </div>
    </div>

    @if($hint)
        <small class="form-text text-muted">{{ $hint }}</small>
    @endif
</div>

@once
    @push('styles')
    <style>
        .translatable-field .nav-tabs {
            margin-bottom: 1rem;
        }
        .translatable-field .tab-content {
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 .25rem .25rem;
        }
        .translatable-field .editor-container {
            min-height: 300px;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تهيئة CKEditor للحقول من نوع editor
            document.querySelectorAll('.editor').forEach(function(element) {
                ClassicEditor
                    .create(element, {
                        language: element.dir === 'rtl' ? 'ar' : 'en',
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });

            // إضافة لغة ديناميكيًا
            document.querySelectorAll('[id^="add-lang-btn-"]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const fieldId = btn.id.replace('add-lang-btn-', '');
                    const tabs = document.getElementById('translatable-tabs-' + fieldId);
                    const tabsContent = document.getElementById('translatable-tabs-content-' + fieldId);
                    let newLocale = prompt('أدخل رمز اللغة (مثال: fr, de, tr):');
                    if (!newLocale) return;
                    newLocale = newLocale.trim().toLowerCase();
                    if (!/^[a-z]{2,5}$/.test(newLocale)) {
                        alert('رمز اللغة غير صالح.');
                        return;
                    }
                    if (document.getElementById('tab-' + fieldId + '-' + newLocale)) {
                        alert('هذه اللغة مضافة بالفعل.');
                        return;
                    }
                    // إضافة التبويب
                    const li = document.createElement('li');
                    li.className = 'nav-item';
                    li.id = `nav-item-${fieldId}-${newLocale}`;
                    li.innerHTML = `<a class=\"nav-link\" id=\"tab-${fieldId}-${newLocale}\" data-toggle=\"tab\" href=\"#content-${fieldId}-${newLocale}\" role=\"tab\" aria-controls=\"content-${fieldId}-${newLocale}\" aria-selected=\"false\">${newLocale.toUpperCase()}</a><button type=\"button\" class=\"btn btn-link btn-sm text-danger p-0 ml-1 remove-lang-btn\" title=\"حذف اللغة\" data-locale=\"${newLocale}\" data-fieldid=\"${fieldId}\"><i class=\"fas fa-times\"></i></button>`;
                    tabs.insertBefore(li, btn.parentElement);
                    // إضافة المحتوى
                    const div = document.createElement('div');
                    div.className = 'tab-pane fade';
                    div.id = `content-${fieldId}-${newLocale}`;
                    div.setAttribute('role', 'tabpanel');
                    div.setAttribute('aria-labelledby', `tab-${fieldId}-${newLocale}`);
                    let inputHtml = '';
                    @if($type === 'textarea')
                        inputHtml = `<textarea name=\"{{ $name }}[${newLocale}]\" id=\"${fieldId}_${newLocale}\" class=\"form-control\" rows=\"3\" dir=\"ltr\" placeholder=\"{{ $placeholder }}\"></textarea>`;
                    @elseif($type === 'editor')
                        inputHtml = `<div class=\"editor-container\"><textarea name=\"{{ $name }}[${newLocale}]\" id=\"${fieldId}_${newLocale}\" class=\"editor\" dir=\"ltr\"></textarea></div>`;
                    @elseif($type === 'image')
                        inputHtml = `<input type=\"file\" accept=\"image/*\" name=\"{{ $name }}[${newLocale}]\" id=\"${fieldId}_${newLocale}_image\" class=\"form-control-file\">`;
                    @else
                        inputHtml = `<input type=\"{{ $type }}\" name=\"{{ $name }}[${newLocale}]\" id=\"${fieldId}_${newLocale}\" class=\"form-control\" dir=\"ltr\" placeholder=\"{{ $placeholder }}\">`;
                    @endif
                    div.innerHTML = inputHtml;
                    tabsContent.appendChild(div);
                    // تفعيل التبويب الجديد
                    tabs.querySelectorAll('.nav-link').forEach(a => a.classList.remove('active'));
                    li.querySelector('a').classList.add('active');
                    tabsContent.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('show', 'active'));
                    div.classList.add('show', 'active');
                    // إذا كان محرر، فعّل CKEditor
                    if (inputHtml.includes('editor')) {
                        setTimeout(function() {
                            ClassicEditor.create(div.querySelector('.editor'), {language: 'en'}).catch(console.error);
                        }, 100);
                    }
                });
            });

            // حذف لسان لغة ديناميكيًا
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-lang-btn')) {
                    const btn = e.target.closest('.remove-lang-btn');
                    const locale = btn.getAttribute('data-locale');
                    const fieldId = btn.getAttribute('data-fieldid');
                    // تأكيد الحذف
                    if (!confirm('هل أنت متأكد من حذف هذه اللغة؟ سيتم حذف جميع البيانات المدخلة لهذا اللسان.')) {
                        return;
                    }
                    // حذف التبويب
                    const navItem = document.getElementById('nav-item-' + fieldId + '-' + locale);
                    if (navItem) navItem.remove();
                    // حذف المحتوى
                    const contentDiv = document.getElementById('content-' + fieldId + '-' + locale);
                    if (contentDiv) contentDiv.remove();
                    // تفعيل أول تبويب متبقٍ
                    const tabs = document.getElementById('translatable-tabs-' + fieldId);
                    const tabsContent = document.getElementById('translatable-tabs-content-' + fieldId);
                    const firstTab = tabs.querySelector('.nav-link:not([id^=\'add-lang-btn-\'])');
                    const firstPane = tabsContent.querySelector('.tab-pane');
                    if (firstTab && firstPane) {
                        tabs.querySelectorAll('.nav-link').forEach(a => a.classList.remove('active'));
                        firstTab.classList.add('active');
                        tabsContent.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('show', 'active'));
                        firstPane.classList.add('show', 'active');
                    }
                }
            });
        });
    </script>
    @endpush
@endonce
