<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('client-applications.heading_show') }}: {{ $app->name }}

            <x-slot:buttons>
                @can('viewAny', \App\Models\PrintServer::class)
                    <x-layout.header.button href="{{ route('web-print.apps.index') }}">
                        {{ __('common.buttons.back-to-list') }}
                    </x-layout.header.button>
                @endcan
            </x-slot>
        </x-layout.header>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">


            <div class="mt-10 sm:mt-0">
                <livewire:apps.update-app :app="$app" />
            </div>

            <x-jet-section-border />

            @can('generateToken', $app)
                <div class="mt-10 sm:mt-0">
                    <livewire:apps.generate-token-for-app :app="$app" />
                </div>

                <x-jet-section-border />
            @endcan

            @can('delete', $app)
                <div class="mt-10 sm:mt-0">
                    <livewire:apps.delete-app :app="$app" />
                </div>

                <x-jet-section-border />
            @endcan

        </div>
    </div>
</x-app-layout>
