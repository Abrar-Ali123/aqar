@props(['settings', 'content', 'facility'])

<div class="contact-component">
    <form action="{{ route('facilities.contact', $facility) }}" method="POST" class="space-y-6">
        @csrf
        
        @foreach($settings['fields'] as $field)
            <div class="form-group">
                <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700">
                    {{ $field['label'] }}
                </label>
                
                @if($field['type'] === 'textarea')
                    <textarea
                        id="{{ $field['name'] }}"
                        name="{{ $field['name'] }}"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        required
                    ></textarea>
                @else
                    <input
                        type="{{ $field['type'] }}"
                        id="{{ $field['name'] }}"
                        name="{{ $field['name'] }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        required
                    >
                @endif
            </div>
        @endforeach

        <div class="flex items-center justify-end">
            <button type="submit" class="btn btn-primary">
                إرسال الرسالة
            </button>
        </div>
    </form>

    @if(session('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ $settings['success_message'] ?? session('success') }}
        </div>
    @endif
</div>
