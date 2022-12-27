<div wire:poll.visible="refresh" wire:init="refresh" class="mt-16">
    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('dashboard.servers.heading') }}</h3>
    @if($servers !== null)
        <x-card-collection class="mt-5" grid="4">
            @foreach($servers as $server)
                <x-print-servers.card :server="$server" :printers_button="false"/>
            @endforeach

            @if($servers->count() < 4)
                @can('create', \App\Models\PrintServer::class)
                    <x-print-servers.create-card/>
                @endcan
            @endif
        </x-card-collection>
    @else
        <x-loading-card class="mt-5"/>
    @endif
</div>
