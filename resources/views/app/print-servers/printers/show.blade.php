<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('printers.heading_show') }}: {{ $printer->name }} @ {{ $server->name }}

            <x-slot:buttons>
                @can('viewAny', [\App\Models\Printer::class, $server])
                    <x-layout.header.button href="{{ route('web-print.servers.printers.index', $server) }}">
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
