<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('print-servers.heading_create') }}

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



        </div>
    </div>
</x-app-layout>
