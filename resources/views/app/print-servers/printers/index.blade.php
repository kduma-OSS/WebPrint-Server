<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Printers') }} @ {{ $server->name }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">





            @if($printers->count())
                <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($printers as $printer)
                        <li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
                            <div class="flex w-full items-center justify-between space-x-6 p-6">
                                <div class="flex-1 truncate">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="truncate text-sm font-medium text-gray-900">
                                            {{ $printer->name }}
                                        </h3>
                                        @if($printer->enabled)
                                            <span class="inline-block flex-shrink-0 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 truncate">
                                                {{ strtoupper(implode(', ', array_merge($printer->ppd_support ? ['ppd'] : [],$printer->raw_languages_supported))) }}
                                            </span>
                                        @elseif($printer->last_active_at->diffInMinutes() > 3)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Disabled
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-1 truncate text-sm text-gray-500">
                                        {{ $printer->location ?? __('No Location') }}
                                    </p>
                                </div>
                                {{--                            <img class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60" alt="">--}}
                            </div>
                            <div>
                                <div class="-mt-px flex divide-x divide-gray-200">
                                    @can('view', $printer)
                                        <div class="flex w-0 flex-1">
                                            <a href="{{ route('web-print.printers.show', $printer) }}" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center rounded-bl-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                                                <!-- Heroicon name: mini/printer -->
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.097 1.126.153A2.212 2.212 0 0118 8.653v4.097A2.25 2.25 0 0115.75 15h-.241l.305 1.984A1.75 1.75 0 0114.084 19H5.915a1.75 1.75 0 01-1.73-2.016L4.492 15H4.25A2.25 2.25 0 012 12.75V8.653c0-1.082.775-2.034 1.874-2.198.374-.056.75-.107 1.127-.153L5 6.25v-3.5zm8.5 3.397a41.533 41.533 0 00-7 0V2.75a.25.25 0 01.25-.25h6.5a.25.25 0 01.25.25v3.397zM6.608 12.5a.25.25 0 00-.247.212l-.693 4.5a.25.25 0 00.247.288h8.17a.25.25 0 00.246-.288l-.692-4.5a.25.25 0 00-.247-.212H6.608z" clip-rule="evenodd" />
                                                </svg>

                                                <span class="ml-3">View Printer</span>
                                            </a>
                                        </div>
                                    @endcan
                                    @can('viewAny', [\App\Models\PrintJob::class])
                                        <div class="-ml-px flex w-0 flex-1">
                                            <a href="{{ route('web-print.jobs.index', ['printer' => $printer]) }}" class="relative inline-flex w-0 flex-1 items-center justify-center rounded-br-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                                                <!-- Heroicon name: mini/newspaper -->
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h9A1.5 1.5 0 0114 3.5v11.75A2.75 2.75 0 0016.75 18h-12A2.75 2.75 0 012 15.25V3.5zm3.75 7a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5zm0 3a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5zM5 5.75A.75.75 0 015.75 5h4.5a.75.75 0 01.75.75v2.5a.75.75 0 01-.75.75h-4.5A.75.75 0 015 8.25v-2.5z" clip-rule="evenodd" />
                                                    <path d="M16.5 6.5h-1v8.75a1.25 1.25 0 102.5 0V8a1.5 1.5 0 00-1.5-1.5z" />
                                                </svg>

                                                <span class="ml-3">Jobs ({{ $printer->jobs_count }})</span>
                                            </a>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                @can('create', [\App\Models\Printer::class, $server])
                    <a href="{{ route('web-print.servers.printers.create', $server) }}" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="mx-auto h-12 w-12 text-gray-400"  aria-hidden="true">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                        </svg>
                        <span class="mt-2 block text-sm font-medium text-gray-900">Create a new printer</span>
                    </a>
                @else
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="mx-auto h-12 w-12 text-gray-400"  aria-hidden="true">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No printers</h3>
                        <p class="mt-1 text-sm text-gray-500">You don't have permission to create new printers.</p>
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
