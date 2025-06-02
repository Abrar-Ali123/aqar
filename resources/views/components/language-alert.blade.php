@if(session()->has('language-changed'))
<div x-data="{ show: true }" 
     x-show="show" 
     x-init="setTimeout(() => show = false, 3000)"
     class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" 
     style="z-index: 1050;"
     role="alert">
    {{ session('language-changed.message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="show = false"></button>
</div>
@endif

<div 
    x-data="{ show: false, message: '', direction: '' }" 
    x-show="show"
    x-init="
        window.addEventListener('language-changed', (e) => {
            message = e.detail.message;
            direction = e.detail.direction;
            show = true;
            setTimeout(() => show = false, 3000);
        })
    "
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-2"
    class="fixed top-4 right-4 z-50"
>
    <div 
        class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2"
        :class="{ 'flex-row-reverse space-x-reverse': direction === 'rtl' }"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span x-text="message"></span>
    </div>
</div>
