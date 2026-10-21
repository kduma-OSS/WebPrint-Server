<x-form-section submit="updateGeneralSettings">

    <x-slot name="title">
        {{ __('settings.general.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('settings.general.description') }}
    </x-slot>

    <x-slot name="form">
        @canany(['view', 'update'], [\App\Settings\GeneralSettings::class, 'site_name'])
            <!-- Site Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="site_name" value="{{ __('settings.general.site-name') }}" />
                @can('update', [\App\Settings\GeneralSettings::class, 'site_name'])
                    <x-input id="site_name" type="text" class="mt-1 block w-full" wire:model="site_name" />
                @else
                    <x-input id="site_name" type="text" class="mt-1 block w-full" wire:model="site_name" disabled />
                @endcan
                <x-input-error for="site_name" class="mt-2" />
            </div>
        @endcanany

        @canany(['view', 'update'], [\App\Settings\GeneralSettings::class, 'active'])
            <!-- Application Active -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="active" value="{{ __('settings.general.application-active') }}" />
                @can('update', [\App\Settings\GeneralSettings::class, 'active'])
                    <x-checkbox id="active" class="mt-3" wire:model="active" />
                @else
                    <x-checkbox id="active" class="mt-3" wire:model="active" disabled />
                @endcan
                <x-input-error for="active" class="mt-2" />
            </div>
        @endcanany

        @canany(['view', 'update'], [\App\Settings\GeneralSettings::class, 'language'])
            <!-- Application Language -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="language" value="{{ __('settings.general.language') }}" />
                @can('update', [\App\Settings\GeneralSettings::class, 'language'])
                    <x-input id="language" type="text" class="mt-1 block w-full" wire:model="language" />
                @else
                    <x-input id="language" type="text" class="mt-1 block w-full" wire:model="language" disabled />
                @endcan
                <x-input-error for="language" class="mt-2" />
            </div>
        @endcanany
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button>
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
