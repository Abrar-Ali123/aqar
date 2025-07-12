<!-- Self-contained component dependencies -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<!-- Tailwind CSS and Google Fonts for self-contained styling -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
<style>
    /* Applying Cairo font to the component's container */
    .font-cairo {
        font-family: 'Cairo', sans-serif;
    }
</style>

{{--
    This component is based on the user-provided HTML/JS example.
    It uses Tailwind CSS and Vanilla JavaScript.
    NOTE: Ensure your main layout file includes Tailwind CSS for this to render correctly.
--}}

<div class="max-w-3xl mx-auto font-cairo">
    <!-- This is the container where repeated fields will be added -->
    <div id="language-fields-wrapper-{{ $fieldId }}" class="space-y-4">
        <!-- Fields will be dynamically inserted here -->
    </div>

    <!-- Button to add a new field group -->
    <button type="button" id="add-language-btn-{{ $fieldId }}" class="flex items-center justify-center w-full gap-2 px-4 py-3 mt-6 font-semibold text-white transition-colors duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
        <span>{{ __('Add New Language') }}</span>
    </button>
</div>

<!-- Template for the language fields -->
<template id="language-field-template-{{ $fieldId }}">
    <div class="language-field-group flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg animate-fade-in">
        <select class="language-select block w-1/4 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @foreach($availableLanguageNames as $code => $langName)
                <option value="{{ $code }}">{{ $langName }}</option>
            @endforeach
        </select>
        
        @if ($type === 'textarea')
            <textarea placeholder="{{ $placeholder }}" class="flex-grow p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3"></textarea>
        @elseif ($type === 'rich-editor')
            <div class="flex-grow" wire:ignore>
                 <textarea placeholder="{{ $placeholder }}" class="ck-target flex-grow p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
        @else
            <input type="text" placeholder="{{ $placeholder }}" class="flex-grow p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        @endif

        <button type="button" class="remove-btn flex-shrink-0 p-2 text-white bg-red-500 rounded-full hover:bg-red-600 transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
        </button>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- Configuration Passed from Blade --- //
        const config = {
        fieldId: @json($fieldId),
        name: @json($name),
        type: @json($type),
        placeholder: @json($placeholder),
        defaultLocale: @json($defaultLocale),
        allAvailableLocales: @json($allAvailableLocales),
        initialLocales: @json($initialLocales),
        initialValues: @json(json_decode($values, true)),
        availableLanguages: @json($availableLanguageNames)
    };

    // --- DOM Elements --- //
    const fieldsWrapper = document.getElementById(`language-fields-wrapper-${config.fieldId}`);
    const addBtn = document.getElementById(`add-language-btn-${config.fieldId}`);

    // --- State --- //
    let editors = {};

    // --- Functions --- //
    const getUsedLanguages = () => Array.from(fieldsWrapper.querySelectorAll('.language-select')).map(s => s.value);

    const updateAddButtonState = () => {
        const used = getUsedLanguages();
        addBtn.disabled = used.length >= config.allAvailableLocales.length;
    };

    const initCkeditor = (element, locale) => {
        if (config.type !== 'rich-editor') return;
        if (typeof ClassicEditor === 'undefined') {
            return console.warn('CKEditor not loaded, rich text functionality is disabled.');
        }
        if (editors[locale]) editors[locale].destroy().catch(() => {});
        ClassicEditor.create(element, { language: locale })
            .then(editor => { editors[locale] = editor; })
            .catch(error => console.error(`CKEditor init error for ${locale}:`, error));
    };

    const createField = (locale, value = '') => {
        const isDefault = locale === config.defaultLocale;
        const newField = document.createElement('div');
        newField.className = 'language-field-group p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300';
        newField.style.opacity = '0';

        const availableOptions = Object.entries(config.availableLanguages)
            .filter(([code, name]) => code === locale || !getUsedLanguages().includes(code))
            .map(([code, name]) => `<option value="${code}" ${code === locale ? 'selected' : ''}>${name}</option>`).join('');

        let inputHtml;
        const inputName = `${config.name}[${locale}]`;

        switch (config.type) {
            case 'textarea':
                inputHtml = `<textarea name="${inputName}" class="mt-2 w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" rows="4" placeholder="${config.placeholder}">${value}</textarea>`;
                break;
            case 'rich-editor':
                inputHtml = `<div class="ck-editor-container"><textarea name="${inputName}" id="editor-${config.fieldId}-${locale}">${value}</textarea></div>`;
                break;
            default:
                inputHtml = `<input type="text" name="${inputName}" value="${value}" class="mt-2 w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="${config.placeholder}">`;
        }

        newField.innerHTML = `
            <div class="flex items-center justify-between">
                <select class="language-select p-2 border border-gray-300 rounded-md bg-white" ${isDefault ? 'disabled' : ''}>
                    ${availableOptions}
                </select>
                <button type="button" class="remove-language-btn text-red-500 hover:text-red-700 ${isDefault ? 'hidden' : ''}" aria-label="Remove Language">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </button>
            </div>
            ${inputHtml}
        `;

        fieldsWrapper.appendChild(newField);
        setTimeout(() => newField.style.opacity = '1', 50);

        if (config.type === 'rich-editor') {
            const textarea = newField.querySelector(`#editor-${config.fieldId}-${locale}`);
            initCkeditor(textarea, locale);
        }

        newField.querySelector('.language-select').addEventListener('change', (e) => {
            const oldLocale = locale;
            const newLocale = e.target.value;
            const currentEditor = editors[oldLocale];
            let content = '';

            if (config.type === 'rich-editor' && currentEditor) {
                content = currentEditor.getData();
            } else {
                const input = newField.querySelector(`[name='${config.name}[${oldLocale}]']`);
                content = input ? input.value : '';
            }

            createField(newLocale, content);
            newField.remove();

            if (editors[oldLocale]) {
                editors[oldLocale].destroy().catch(() => {});
                delete editors[oldLocale];
            }
            updateAddButtonState();
        });

        if (!isDefault) {
            newField.querySelector('.remove-language-btn').addEventListener('click', () => {
                newField.style.opacity = '0';
                setTimeout(() => {
                    newField.remove();
                    if (editors[locale]) {
                        editors[locale].destroy().catch(()=>{});
                        delete editors[locale];
                    }
                    updateAddButtonState();
                }, 300);
            });
        }
    };

    // --- Initialization --- //
    addBtn.addEventListener('click', () => {
        const used = getUsedLanguages();
        const nextLocale = config.allAvailableLocales.find(lang => !used.includes(lang));
        if (nextLocale) {
            createField(nextLocale);
            updateAddButtonState();
        }
    });

    config.initialLocales.forEach(locale => {
        const value = config.initialValues[locale] || '';
        createField(locale, value);
    });

    updateAddButtonState();
});
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.4s ease-out;
    }
</style>