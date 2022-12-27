<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('navigation.client-apps') }}
            <x-slot:buttons>
                @can('create', \App\Models\ClientApplication::class)
                    <x-layout.header.button href="{{ route('web-print.apps.create') }}">
                        {{ __('common.buttons.new') }}
                    </x-layout.header.button>
                @endcan
            </x-slot>
        </x-layout.header>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">





            @if($apps->count())
                <x-card-collection>
                    @foreach($apps as $app)
                        <x-apps.card :app="$app"/>
                    @endforeach
                    @can('create', \App\Models\ClientApplication::class)
                        <x-apps.create-card/>
                    @endcan
                </x-card-collection>
            @else
                @can('create', \App\Models\ClientApplication::class)
                    <a href="{{ route('web-print.apps.create') }}" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="mx-auto h-12 w-12 text-gray-400"  aria-hidden="true">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <span class="mt-2 block text-sm font-medium text-gray-900">{{ __('client-applications.create-new-client-application-label') }}</span>
                    </a>
                @else
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="mx-auto h-12 w-12 text-gray-400"  aria-hidden="true">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('client-applications.no-client-applications-label') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('client-applications.no-permissions-to-create-application-label') }}</p>
                        {{--                <div class="mt-6">--}}
                        {{--                    <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">--}}
                        {{--                        <!-- Heroicon name: mini/plus -->--}}
                        {{--                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">--}}
                        {{--                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />--}}
                        {{--                        </svg>--}}
                        {{--                        New Project--}}
                        {{--                    </button>--}}
                        {{--                </div>--}}
                    </div>
                @endcan
            @endif










        </div>
    </div>
</x-app-layout>
