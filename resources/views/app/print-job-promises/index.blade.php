<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Print Promises') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">







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
                                Client
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
                        @foreach($promises as $promise)
                            <tr>
                                <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-6">
                                    {{ $promise->name }}
                                    <dl class="font-normal lg:hidden">
                                        <dt class="sr-only">Status</dt>
                                        <dd class="mt-1 truncate text-gray-700">
                                            @switch($promise->status)
                                                @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Draft)
                                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                      Draft
                                                    </span>
                                                    @break
                                                @case(\App\Models\Enums\PrintJobPromiseStatusEnum::New)
                                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                      New
                                                    </span>
                                                    @break
                                                @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Ready)
                                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                      Ready
                                                    </span>
                                                    @break
                                                @case(\App\Models\Enums\PrintJobPromiseStatusEnum::SentToPrinter)
                                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                      Sent to printer
                                                    </span>
                                                    @break
                                                @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Cancelled)
                                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                      Cancelled
                                                    </span>
                                                    @break
                                                @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Failed)
                                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                      Printing Failed
                                                    </span>
                                                    @break
                                            @endswitch
                                        </dd>
                                        <dt class="sr-only">Client</dt>
                                        <dd class="mt-1 truncate text-gray-700">
                                            @if($promise->ClientApplication)
                                                {{ $promise->ClientApplication->name }}
                                            @else
                                                <span class="text-gray-400">No Client</span>
                                            @endif
                                        </dd>
                                        <dt class="sr-only sm:hidden">
                                            Printer
                                        </dt>
                                        <dd class="mt-1 truncate text-gray-500 sm:hidden">
                                            @if($promise->Printer)
                                                {{ $promise->Printer->name }}
                                            @else
                                                <span class="text-gray-400">No Printer</span>
                                            @endif
                                        </dd>
                                    </dl>
                                </td>
                                <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                                    @switch($promise->status)
                                        @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Draft)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                              Draft
                                            </span>
                                            @break
                                        @case(\App\Models\Enums\PrintJobPromiseStatusEnum::New)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                              New
                                            </span>
                                            @break
                                        @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Ready)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                              Ready
                                            </span>
                                            @break
                                        @case(\App\Models\Enums\PrintJobPromiseStatusEnum::SentToPrinter)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                              Sent to printer
                                            </span>
                                            @break
                                        @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Cancelled)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                              Cancelled
                                            </span>
                                            @break
                                        @case(\App\Models\Enums\PrintJobPromiseStatusEnum::Failed)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                              Printing Failed
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                                    @if($promise->ClientApplication)
                                        {{ $promise->ClientApplication->name }}
                                    @else
                                        <span class="text-gray-400">No Client</span>
                                    @endif
                                </td>
                                <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                    @if($promise->Printer)
                                        {{ $promise->Printer->name }}
                                    @else
                                        <span class="text-gray-400">No Printer</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    @if($promise->size)
                                        {{ \Coduo\PHPHumanizer\NumberHumanizer::binarySuffix($promise->size) }}
                                    @else
                                        <span class="text-gray-400">n/a</span>
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


{{--            <textarea class="w-full" rows="50">{{ json_encode($promises->first(), JSON_PRETTY_PRINT) }}</textarea>--}}




        </div>
    </div>
</x-app-layout>
