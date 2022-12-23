<x-jet-form-section submit="updateGeneralSettings">

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
                <x-jet-label for="site_name" value="{{ __('settings.general.site-name') }}" />
                @can('update', [\App\Settings\GeneralSettings::class, 'site_name'])
                    <x-jet-input id="site_name" type="text" class="mt-1 block w-full" wire:model.defer="site_name" />
                @else
                    <x-jet-input id="site_name" type="text" class="mt-1 block w-full" wire:model.defer="site_name" disabled />
                @endcan
                <x-jet-input-error for="site_name" class="mt-2" />
            </div>
        @endcanany

        @canany(['view', 'update'], [\App\Settings\GeneralSettings::class, 'active'])
            <!-- Application Active -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="active" value="{{ __('settings.general.application-active') }}" />
                @can('update', [\App\Settings\GeneralSettings::class, 'active'])
                    <x-jet-checkbox id="active" class="mt-3" wire:model.defer="active" />
                @else
                    <x-jet-checkbox id="active" class="mt-3" wire:model.defer="active" disabled />
                @endcan
                <x-jet-input-error for="active" class="mt-2" />
            </div>
        @endcanany

        @canany(['view', 'update'], [\App\Settings\GeneralSettings::class, 'language'])
            <!-- Application Language -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="language" value="{{ __('Application Language') }}" />
                @can('update', [\App\Settings\GeneralSettings::class, 'language'])
                    <x-jet-input id="language" type="text" class="mt-1 block w-full" wire:model.defer="language" />
                @else
                    <x-jet-input id="language" type="text" class="mt-1 block w-full" wire:model.defer="language" disabled />
                @endcan
                <x-jet-input-error for="language" class="mt-2" />
            </div>
        @endcanany
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button>
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
