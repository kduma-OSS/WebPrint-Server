@props([
    'app'
])

<li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
    <div class="flex w-full items-center justify-between space-x-6 p-6">
        <div class="flex-1 truncate">
            <div class="flex items-center space-x-3">
                <h3 class="truncate text-sm font-medium text-gray-900">
                    {{ $app->name }}
                </h3>
{{--                                        @if($app->enabled)--}}
{{--                                            <span class="inline-block flex-shrink-0 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 truncate">--}}
{{--                                                {{ strtoupper(implode(', ', array_merge($app->ppd_support ? ['ppd'] : [],$app->raw_languages_supported))) }}--}}
{{--                                            </span>--}}
{{--                                        @elseif($app->last_active_at->diffInMinutes() > 3)--}}
{{--                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">--}}
{{--                                                Disabled--}}
{{--                                            </span>--}}
{{--                                        @endif--}}
            </div>
            <p class="mt-1 truncate text-sm text-gray-500">
                @if($app->url)
                    <a href="{{ $app->url }}" class="text-gray-500 hover:text-gray-900" target="_blank">
                        {{ $app->url_domain }}
                    </a>
                @else
                    {{ __('client-applications.no-url-label') }}
                @endif
            </p>
        </div>
        {{--                            <img class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60" alt="">--}}
    </div>
    <div>
        <div class="-mt-px flex divide-x divide-gray-200">
            @can('view', $app)
                <div class="flex w-0 flex-1">
                    <a href="{{ route('web-print.apps.show', $app) }}"
                       class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center rounded-bl-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                        <!-- Heroicon name: mini/globe-alt -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 text-gray-400" aria-hidden="true">
                            <path d="M16.555 5.412a8.028 8.028 0 00-3.503-2.81 14.899 14.899 0 011.663 4.472 8.547 8.547 0 001.84-1.662zM13.326 7.825a13.43 13.43 0 00-2.413-5.773 8.087 8.087 0 00-1.826 0 13.43 13.43 0 00-2.413 5.773A8.473 8.473 0 0010 8.5c1.18 0 2.304-.24 3.326-.675zM6.514 9.376A9.98 9.98 0 0010 10c1.226 0 2.4-.22 3.486-.624a13.54 13.54 0 01-.351 3.759A13.54 13.54 0 0110 13.5c-1.079 0-2.128-.127-3.134-.366a13.538 13.538 0 01-.352-3.758zM5.285 7.074a14.9 14.9 0 011.663-4.471 8.028 8.028 0 00-3.503 2.81c.529.638 1.149 1.199 1.84 1.66zM17.334 6.798a7.973 7.973 0 01.614 4.115 13.47 13.47 0 01-3.178 1.72 15.093 15.093 0 00.174-3.939 10.043 10.043 0 002.39-1.896zM2.666 6.798a10.042 10.042 0 002.39 1.896 15.196 15.196 0 00.174 3.94 13.472 13.472 0 01-3.178-1.72 7.973 7.973 0 01.615-4.115zM10 15c.898 0 1.778-.079 2.633-.23a13.473 13.473 0 01-1.72 3.178 8.099 8.099 0 01-1.826 0 13.47 13.47 0 01-1.72-3.178c.855.151 1.735.23 2.633.23zM14.357 14.357a14.912 14.912 0 01-1.305 3.04 8.027 8.027 0 004.345-4.345c-.953.542-1.971.981-3.04 1.305zM6.948 17.397a8.027 8.027 0 01-4.345-4.345c.953.542 1.971.981 3.04 1.305a14.912 14.912 0 001.305 3.04z"/>
                        </svg>

                        <span class="ml-3">{{ __('common.buttons.view-app') }}</span>
                    </a>
                </div>
            @endcan
            @can('viewAny', [\App\Models\PrintJob::class])
                <div class="-ml-px flex w-0 flex-1">
                    <a href="{{ route('web-print.jobs.index', ['app' => $app]) }}"
                       class="relative inline-flex w-0 flex-1 items-center justify-center rounded-br-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                        <!-- Heroicon name: mini/newspaper -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5 text-gray-400" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M2 3.5A1.5 1.5 0 013.5 2h9A1.5 1.5 0 0114 3.5v11.75A2.75 2.75 0 0016.75 18h-12A2.75 2.75 0 012 15.25V3.5zm3.75 7a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5zm0 3a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5zM5 5.75A.75.75 0 015.75 5h4.5a.75.75 0 01.75.75v2.5a.75.75 0 01-.75.75h-4.5A.75.75 0 015 8.25v-2.5z"
                                  clip-rule="evenodd"/>
                            <path d="M16.5 6.5h-1v8.75a1.25 1.25 0 102.5 0V8a1.5 1.5 0 00-1.5-1.5z"/>
                        </svg>

                        <span class="ml-3">{{ __('common.buttons.jobs') }} ({{ $app->jobs_count }})</span>
                    </a>
                </div>
            @endcan
        </div>
    </div>
</li>
