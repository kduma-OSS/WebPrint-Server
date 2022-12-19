<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Print Jobs') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">






            @if($jobs->count())
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="-mx-4 mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    Name
                                </th>
                                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                                    Status
                                </th>
                                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                                    Client / Promise
                                </th>
                                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                                    Printer
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Size</th>
    {{--                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">--}}
    {{--                                <span class="sr-only">Edit</span>--}}
    {{--                            </th>--}}
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($jobs as $job)
                                    <tr>
                                        <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-6">
                                            {{ $job->name }}
                                            <dl class="font-normal lg:hidden">
                                                <dt class="sr-only">Status</dt>
                                                <dd class="mt-1 truncate text-gray-700">
                                                    @switch($job->status)
                                                        @case(\App\Models\Enums\PrintJobStatusEnum::New)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                          Waiting in print queue<span class="hidden sm:table-cell">: {{ $job->updated_at->diffForHumans() }}</span>
                                                        </span>
                                                        @break
                                                        @case(\App\Models\Enums\PrintJobStatusEnum::Printing)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                          Currently processing
                                                        </span>
                                                        @break
                                                        @case(\App\Models\Enums\PrintJobStatusEnum::Finished)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                          Job sent to printer
                                                        </span>
                                                        @break
                                                        @case(\App\Models\Enums\PrintJobStatusEnum::Failed)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                          Printing Failed{{ $job->status_message ? ': ' : '' }} <i>{{ $job->status_message }}</i>
                                                        </span>
                                                        @break
                                                    @endswitch
                                                </dd>
                                                <dt class="sr-only">Client / Promise</dt>
                                                <dd class="mt-1 truncate text-gray-700">
                                                    @if($job->JobPromise)
                                                        {{ $job->JobPromise->name }}
                                                    @elseif($job->ClientApplication)
                                                        <span class="text-amber-500">
                                                            {{ $job->ClientApplication->name }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">No Client / Promise</span>
                                                    @endif
                                                </dd>
                                                <dt class="sr-only sm:hidden">
                                                    Printer
                                                </dt>
                                                <dd class="mt-1 truncate text-gray-500 sm:hidden">
                                                    {{ $job->Printer->name }}
                                                </dd>
                                            </dl>
                                        </td>
                                        <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                                            @switch($job->status)
                                                @case(\App\Models\Enums\PrintJobStatusEnum::New)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                  Waiting in print queue:
                                                  {{ $job->updated_at->diffForHumans() }}
                                                </span>
                                                @break
                                                @case(\App\Models\Enums\PrintJobStatusEnum::Printing)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                  Currently processing
                                                </span>
                                                @break
                                                @case(\App\Models\Enums\PrintJobStatusEnum::Finished)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                  Job sent to printer
                                                </span>
                                                @break
                                                @case(\App\Models\Enums\PrintJobStatusEnum::Failed)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                  Printing Failed{{ $job->status_message ? ': ' : '' }} <i>{{ $job->status_message }}</i>
                                                </span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                                            @if($job->JobPromise)
                                                {{ $job->JobPromise->name }}
                                            @elseif($job->ClientApplication)
                                                <span class="text-amber-500">
                                                    {{ $job->ClientApplication->name }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">No Client / Promise</span>
                                            @endif
                                        </td>
                                        <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                            {{ $job->Printer->name }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-500">
                                            {{ \Coduo\PHPHumanizer\NumberHumanizer::binarySuffix($job->size) }}
                                        </td>
    {{--                                    <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">--}}
    {{--                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, {{ $job->name }}</span></a>--}}
    {{--                                    </td>--}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8">
                        {{ $jobs->onEachSide(1)->withQueryString()->links() }}
                    </div>
                </div>
            @else
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No print jobs</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by using API to create print jobs.</p>
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
            @endif



            {{--            <textarea class="w-full" rows="50">{{ json_encode($jobs, JSON_PRETTY_PRINT) }}</textarea>--}}




        </div>
    </div>
</x-app-layout>
