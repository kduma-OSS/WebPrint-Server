<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('navigation.print-promises') }}
        </x-layout.header>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">






            @if($promises->count())
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="-mx-4 mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    {{ __('print-jobs-promises.headings.name') }}
                                </th>
                                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                                    {{ __('print-jobs-promises.headings.status') }}
                                </th>
                                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                                    {{ __('print-jobs-promises.headings.client') }}
                                </th>
                                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                                    {{ __('print-jobs-promises.headings.printer') }}
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    {{ __('print-jobs-promises.headings.size') }}
                                </th>
                                {{--                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">--}}
                                {{--                                <span class="sr-only">Edit</span>--}}
                                {{--                            </th>--}}
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($promises as $promise)
                                <tr>
                                    <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-6">
                                        {{ $promise->name }}
                                        <dl class="font-normal lg:hidden">
                                            <dt class="sr-only">{{ __('print-jobs-promises.headings.status') }}</dt>
                                            <dd class="mt-1 truncate text-gray-700">
                                                @switch($promise->status)
                                                    @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Draft)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                          {{ __('common.enum.promise.draft') }}
                                                        </span>
                                                        @break
                                                    @case(\App\Models\Enums\PrintJobPromiseStatusEnum::New)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                          {{ __('common.enum.promise.new') }}
                                                        </span>
                                                        @break
                                                    @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Ready)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                          {{ __('common.enum.promise.ready') }}
                                                        </span>
                                                        @break
                                                    @case(\App\Models\Enums\PrintJobPromiseStatusEnum::SentToPrinter)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                          {{ __('common.enum.promise.sent-to-printer') }}
                                                        </span>
                                                        @break
                                                    @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Cancelled)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                          {{ __('common.enum.promise.cancelled') }}
                                                        </span>
                                                        @break
                                                    @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Failed)
                                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                          {{ __('common.enum.promise.failed') }}
                                                        </span>
                                                        @break
                                                @endswitch
                                            </dd>
                                            <dt class="sr-only">{{ __('print-jobs-promises.headings.client') }}</dt>
                                            <dd class="mt-1 truncate text-gray-700">
                                                @if($promise->ClientApplication)
                                                    {{ $promise->ClientApplication->name }}
                                                @else
                                                    <span class="text-gray-400">{{ __('print-jobs-promises.no-client-label') }}</span>
                                                @endif
                                            </dd>
                                            <dt class="sr-only sm:hidden">
                                                {{ __('print-jobs-promises.headings.printer') }}
                                            </dt>
                                            <dd class="mt-1 truncate text-gray-500 sm:hidden">
                                                @if($promise->Printer)
                                                    {{ $promise->Printer->name }}
                                                @else
                                                    <span class="text-gray-400">{{ __('print-jobs-promises.no-printer-label') }}</span>
                                                @endif
                                            </dd>
                                        </dl>
                                    </td>
                                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                                        @switch($promise->status)
                                            @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Draft)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                  {{ __('common.enum.promise.draft') }}
                                                </span>
                                                @break
                                            @case(\App\Models\Enums\PrintJobPromiseStatusEnum::New)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                  {{ __('common.enum.promise.new') }}
                                                </span>
                                                @break
                                            @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Ready)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                  {{ __('common.enum.promise.ready') }}
                                                </span>
                                                @break
                                            @case(\App\Models\Enums\PrintJobPromiseStatusEnum::SentToPrinter)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                  {{ __('common.enum.promise.sent-to-printer') }}
                                                </span>
                                                @break
                                            @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Cancelled)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                  {{ __('common.enum.promise.cancelled') }}
                                                </span>
                                                @break
                                            @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Failed)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                  {{ __('common.enum.promise.failed') }}
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                                        @if($promise->ClientApplication)
                                            {{ $promise->ClientApplication->name }}
                                        @else
                                            <span class="text-gray-400">{{ __('print-jobs-promises.no-client-label') }}</span>
                                        @endif
                                    </td>
                                    <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                        @if($promise->Printer)
                                            {{ $promise->Printer->name }}
                                        @else
                                            <span class="text-gray-400">{{ __('print-jobs-promises.no-printer-label') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        @if($promise->size)
                                            {{ \Coduo\PHPHumanizer\NumberHumanizer::binarySuffix($promise->size) }}
                                        @else
                                            <span class="text-gray-400">{{ __('common.n-a') }}</span>
                                        @endif
                                    </td>
                                    {{--                                    <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">--}}
                                    {{--                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, {{ $promise->name }}</span></a>--}}
                                    {{--                                    </td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8">
                        {{ $promises->onEachSide(1)->links() }}
                    </div>
                </div>
            @else
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('print-jobs-promises.no-print-jobs-label') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('print-jobs-promises.get-started-label') }}</p>
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

{{--            <textarea class="w-full" rows="50">{{ json_encode($promises->first(), JSON_PRETTY_PRINT) }}</textarea>--}}




        </div>
    </div>
</x-app-layout>
