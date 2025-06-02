<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">{{ __('nav.about') }}</h1>
                    
                    <div class="prose max-w-none">
                        <p class="mb-4">{{ __('pages.about_intro') }}</p>
                        
                        <h2 class="text-xl font-semibold mt-6 mb-3">{{ __('pages.our_mission') }}</h2>
                        <p class="mb-4">{{ __('pages.mission_text') }}</p>
                        
                        <h2 class="text-xl font-semibold mt-6 mb-3">{{ __('pages.our_vision') }}</h2>
                        <p class="mb-4">{{ __('pages.vision_text') }}</p>
                        
                        <h2 class="text-xl font-semibold mt-6 mb-3">{{ __('pages.our_values') }}</h2>
                        <ul class="list-disc list-inside mb-4">
                            <li class="mb-2">{{ __('pages.value_1') }}</li>
                            <li class="mb-2">{{ __('pages.value_2') }}</li>
                            <li class="mb-2">{{ __('pages.value_3') }}</li>
                            <li class="mb-2">{{ __('pages.value_4') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
