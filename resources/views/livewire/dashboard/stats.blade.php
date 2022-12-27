<div wire:poll.visible="refresh" wire:init="refresh">
    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('dashboard.stats.heading') }}</h3>
    @if($pending_jobs !== null && $finished_jobs !== null && $failed_jobs !== null)
        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">{{ __('dashboard.stats.pending-jobs') }}</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ $pending_jobs !== null ? number_format($pending_jobs) : '...' }}
                </dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">{{ __('dashboard.stats.finished-jobs') }}</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ $finished_jobs !== null ? number_format($finished_jobs) : '...' }}
                </dd>
            </div>

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">{{ __('dashboard.stats.failed-jobs') }}</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ $failed_jobs !== null ? number_format($failed_jobs) : '...' }}
                </dd>
            </div>
        </dl>
    @else
        <x-loading-card class="mt-5"/>
    @endif
</div>
