<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('print-servers.heading_show') }}: {{ $server->name }}

            <x-slot:buttons>
                @can('viewAny', \App\Models\PrintServer::class)
                    <x-layout.header.button href="{{ route('web-print.servers.index') }}">
                        {{ __('common.buttons.back-to-list') }}
                    </x-layout.header.button>
                @endcan
            </x-slot>
        </x-layout.header>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            <div class="mt-10 sm:mt-0">
                <livewire:servers.update-server :server="$server" />
            </div>

            <x-jet-section-border />

            @can('generateToken', $server)
                <div class="mt-10 sm:mt-0">
                    <livewire:servers.generate-token-for-server :server="$server" />
                </div>

                <x-jet-section-border />
            @endcan

            @can('delete', $server)
                <div class="mt-10 sm:mt-0">
                    <livewire:servers.delete-server :server="$server" />
                </div>

                <x-jet-section-border />
            @endcan

        </div>
    </div>
</x-app-layout>
