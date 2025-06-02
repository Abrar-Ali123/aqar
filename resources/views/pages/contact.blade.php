<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">{{ __('nav.contact') }}</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="text-xl font-semibold mb-4">{{ __('pages.contact_info') }}</h2>
                            <div class="space-y-4">
                                <p>
                                    <strong>{{ __('pages.address') }}:</strong><br>
                                    {{ __('pages.company_address') }}
                                </p>
                                <p>
                                    <strong>{{ __('pages.email') }}:</strong><br>
                                    <a href="mailto:info@example.com" class="text-blue-600 hover:text-blue-800">
                                        info@example.com
                                    </a>
                                </p>
                                <p>
                                    <strong>{{ __('pages.phone') }}:</strong><br>
                                    <a href="tel:+966500000000" class="text-blue-600 hover:text-blue-800">
                                        +966 50 000 0000
                                    </a>
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <h2 class="text-xl font-semibold mb-4">{{ __('pages.send_message') }}</h2>
                            <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">
                                        {{ __('pages.name') }}
                                    </label>
                                    <input type="text" name="name" id="name" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">
                                        {{ __('pages.email') }}
                                    </label>
                                    <input type="email" name="email" id="email" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700">
                                        {{ __('pages.subject') }}
                                    </label>
                                    <input type="text" name="subject" id="subject" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700">
                                        {{ __('pages.message') }}
                                    </label>
                                    <textarea name="message" id="message" rows="4" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                                
                                <div>
                                    <button type="submit" 
                                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        {{ __('pages.send') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
