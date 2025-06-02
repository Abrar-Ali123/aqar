@foreach (config('app.locales') as $locale)
    <x-translatable-field name="name" label="اسم العقار" :languages="[ $locale ]" required placeholder="اسم العقار ({{ strtoupper($locale) }})" />
@endforeach

<div class="mb-6">
    <label for="description_{{ $locale }}" class="block text-sm font-medium text-gray-700">الوصف ({{ strtoupper($locale) }}):</label>

    <!-- Rich Text Editor Container -->
    <div class="form-group">
        <!-- Rich Text Tools -->
        <div id="editor-toolbar" class="editor-toolbar mb-2 flex space-x-2">
            <button type="button" onclick="execCmd('bold', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-bold"></i></button>
            <button type="button" onclick="execCmd('italic', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-italic"></i></button>
            <button type="button" onclick="execCmd('underline', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-underline"></i></button>
            <button type="button" onclick="execCmd('justifyLeft', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-align-left"></i></button>
            <button type="button" onclick="execCmd('justifyCenter', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-align-center"></i></button>
            <button type="button" onclick="execCmd('justifyRight', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-align-right"></i></button>
            <button type="button" onclick="execCmd('justifyFull', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-align-justify"></i></button>
            <button type="button" onclick="execCmd('insertUnorderedList', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-list-ul"></i></button>
            <button type="button" onclick="execCmd('insertOrderedList', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-list-ol"></i></button>
            <button type="button" onclick="execCmd('cut', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-cut"></i></button>
            <button type="button" onclick="execCmd('copy', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-copy"></i></button>
            <button type="button" onclick="execCmd('paste', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-paste"></i></button>
            <button type="button" onclick="execCmd('undo', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-undo-alt"></i></button>
            <button type="button" onclick="execCmd('redo', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-redo-alt"></i></button>
        </div>

        <!-- Content Editable Div -->
        <div class="rich-text-editor form-control mt-1 block w-full p-2 border border-gray-300 rounded-md" contenteditable="true" id="description_{{ $locale }}" style="min-height: 100px;" placeholder="{{ __('facility.enter_facility_description', ['locale' => strtoupper($locale)]) }}"></div>

        <!-- Hidden Input Field to Store Data -->
        <input type="hidden" id="hidden_description_{{ $locale }}" name="translations[{{ $locale }}][description]">
    </div>
</div>

<!-- JavaScript to handle the Rich Text Editor Commands and Save Action -->
<script>
    // Function to execute command from the tools
    function execCmd(command, locale) {
        document.execCommand(command, false, null);
        // Update hidden input with contenteditable div's content after change
        document.getElementById('hidden_description_' + locale).value = document.getElementById('description_' + locale).innerHTML;
    }

    // Function to save content into the hidden input field
    function saveContent(locale) {
        var content = document.getElementById('description_' + locale).innerHTML;
        document.getElementById('hidden_description_' + locale).value = content;
    }

    // Event listener to update hidden input whenever the content changes
    document.getElementById('description_{{ $locale }}').addEventListener('input', function() {
        document.getElementById('hidden_description_{{ $locale }}').value = this.innerHTML;
    });
</script>

<!-- Rich Text Editor Style -->
<style>
    .rich-text-editor {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
</style>
