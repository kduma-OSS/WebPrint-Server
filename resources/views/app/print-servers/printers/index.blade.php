<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('printers.heading') }} @ {{ $server->name }}
            <x-slot:buttons>
                @can('view', $server)
                    <x-layout.header.button href="{{ route('web-print.servers.show', $server) }}">
                        {{ __('common.buttons.view-server') }}
                    </x-layout.header.button>
                @endcan
                @can('create', [\App\Models\Printer::class, $server])
                    <x-layout.header.button href="{{ route('web-print.servers.printers.create', $server) }}" multiple="true">
                        {{ __('common.buttons.new') }}
                    </x-layout.header.button>
                @endcan
            </x-slot>
        </x-layout.header>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">





            @if($printers->count())
                <x-card-collection>
                    @foreach($printers as $printer)
                        <x-printers.card :printer="$printer"/>
                    @endforeach
                    @can('create', [\App\Models\Printer::class, $server])
                        <x-printers.create-card :server="$server"/>
                    @endcan
                </x-card-collection>
            @else
                @can('create', [\App\Models\Printer::class, $server])
                    <a href="{{ route('web-print.servers.printers.create', $server) }}" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="mx-auto h-12 w-12 text-gray-400"  aria-hidden="true">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                        </svg>
                        <span class="mt-2 block text-sm font-medium text-gray-900">{{ __('printers.create-new-printer-label') }}</span>
                    </a>
                @else
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="mx-auto h-12 w-12 text-gray-400"  aria-hidden="true">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('printers.no-printers-label') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('printers.no-permissions-to-create') }}</p>
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
