<div wire:poll.visible.10s wire:init="load" class="mt-16">
    <h3 class="text-lg font-medium leading-6 text-gray-900">
        @if($type == 'pending')
            {{ __('dashboard.jobs.pending.heading') }}
        @elseif($type == 'failed')
            {{ __('dashboard.jobs.failed.heading') }}
        @endif
    </h3>
    @if($jobs !== null)
        @if($jobs->count())
            <div class="-mx-4 mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                            {{ __('print-jobs.headings.name') }}
                        </th>
                        <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                            {{ __('print-jobs.headings.status') }}
                        </th>
                        <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                            {{ __('print-jobs.headings.client-promise') }}
                        </th>
                        <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                            {{ __('print-jobs.headings.printer') }}
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                            {{ __('print-jobs.headings.size') }}
                        </th>
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
                                    <dt class="sr-only">{{ __('print-jobs.headings.status') }}</dt>
                                    <dd class="mt-1 truncate text-gray-700">
                                        @switch($job->status)
                                            @case(\App\Models\Enums\PrintJobStatusEnum::New)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                      {{ __('print-dialog.job-status.new') }}<span class="hidden sm:table-cell">: {{ $job->updated_at->diffForHumans() }}</span>
                                                    </span>
                                                @break
                                            @case(\App\Models\Enums\PrintJobStatusEnum::Printing)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                      {{ __('print-dialog.job-status.printing') }}
                                                    </span>
                                                @break
                                            @case(\App\Models\Enums\PrintJobStatusEnum::Finished)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                      {{ __('print-dialog.job-status.finished') }}
                                                    </span>
                                                @break
                                            @case(\App\Models\Enums\PrintJobStatusEnum::Failed)
                                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                      {{ __('print-dialog.job-status.failed') }}{{ $job->status_message ? ': ' : '' }} <i>{{ $job->status_message }}</i>
                                                    </span>
                                                @break
                                        @endswitch
                                    </dd>
                                    <dt class="sr-only">{{ __('print-jobs.headings.client-promise') }}</dt>
                                    <dd class="mt-1 truncate text-gray-700">
                                        @if($job->JobPromise)
                                            {{ $job->JobPromise->name }}
                                        @elseif($job->ClientApplication)
                                            <span class="text-amber-500">
                                                        {{ $job->ClientApplication->name }}
                                                    </span>
                                        @else
                                            <span class="text-gray-400">{{ __('print-jobs.no-client-promise-label') }}</span>
                                        @endif
                                    </dd>
                                    <dt class="sr-only sm:hidden">
                                        {{ __('print-jobs.headings.printer') }}
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
                                              {{ __('print-dialog.job-status.new') }}:
                                              {{ $job->updated_at->diffForHumans() }}
                                            </span>
                                        @break
                                    @case(\App\Models\Enums\PrintJobStatusEnum::Printing)
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                              {{ __('print-dialog.job-status.printing') }}
                                            </span>
                                        @break
                                    @case(\App\Models\Enums\PrintJobStatusEnum::Finished)
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                              {{ __('print-dialog.job-status.finished') }}
                                            </span>
                                        @break
                                    @case(\App\Models\Enums\PrintJobStatusEnum::Failed)
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                              {{ __('print-dialog.job-status.failed') }}{{ $job->status_message ? ': ' : '' }} <i>{{ $job->status_message }}</i>
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
                                    <span class="text-gray-400">{{ __('print-jobs.no-client-promise-label') }}</span>
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
        @else
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    @if($type == 'pending')
                        {{ __('dashboard.jobs.pending.no_pending_jobs') }}
                    @elseif($type == 'failed')
                        {{ __('dashboard.jobs.failed.no_pending_jobs') }}
                    @endif
                </h3>
            </div>
        @endif
    @else
        <x-loading-card class="mt-5" />
    @endif
</div>
