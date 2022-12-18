<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('App Settings') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @can('viewAny', \App\Settings\GeneralSettings::class)
                <div class="mt-10 sm:mt-0">
                    @livewire('settings.general-settings-update-form')
                </div>

                <x-jet-section-border />
            @endcan
            @can('viewAny', \App\Settings\FortifySettings::class)
                <div class="mt-10 sm:mt-0">
                    @livewire('settings.fortify-settings-update-form')
                </div>

                <x-jet-section-border />
            @endcan
        </div>
    </div>
</x-app-layout>
