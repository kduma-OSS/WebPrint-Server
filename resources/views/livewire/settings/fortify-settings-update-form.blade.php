<x-jet-form-section submit="updateFortifySettings">

    <x-slot name="title">
        {{ __('Authentication Settings') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update application authentication settings') }}
    </x-slot>

    <x-slot name="form">
        @canany(['view', 'update'], [\App\Settings\FortifySettings::class, 'registration_enabled'])
            <!-- Users Registration Enabled -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="registration_enabled" value="{{ __('Users Registration Enabled') }}" />
                @can('update', [\App\Settings\FortifySettings::class, 'registration_enabled'])
                    <x-jet-checkbox id="registration_enabled" class="mt-3" wire:model.defer="registration_enabled" />
                @else
                    <x-jet-checkbox id="registration_enabled" class="mt-3" wire:model.defer="registration_enabled" disabled />
                @endcan
                <x-jet-input-error for="registration_enabled" class="mt-2" />
            </div>
        @endcanany
        @canany(['view', 'update'], [\App\Settings\FortifySettings::class, 'password_resets_enabled'])
            <!-- Passwords Resets Enabled -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="password_resets_enabled" value="{{ __('Passwords Resets Enabled') }}" />
                @can('update', [\App\Settings\FortifySettings::class, 'password_resets_enabled'])
                    <x-jet-checkbox id="password_resets_enabled" class="mt-3" wire:model.defer="password_resets_enabled" />
                @else
                    <x-jet-checkbox id="password_resets_enabled" class="mt-3" wire:model.defer="password_resets_enabled" disabled />
                @endcan
                <x-jet-input-error for="password_resets_enabled" class="mt-2" />
            </div>
        @endcanany
        @canany(['view', 'update'], [\App\Settings\FortifySettings::class, 'update_passwords_enabled'])
            <!-- Passwords Updating Enabled -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="update_passwords_enabled" value="{{ __('Passwords Updating Enabled') }}" />
                @can('update', [\App\Settings\FortifySettings::class, 'update_passwords_enabled'])
                    <x-jet-checkbox id="update_passwords_enabled" class="mt-3" wire:model.defer="update_passwords_enabled" />
                @else
                    <x-jet-checkbox id="update_passwords_enabled" class="mt-3" wire:model.defer="update_passwords_enabled" disabled />
                @endcan
                <x-jet-input-error for="update_passwords_enabled" class="mt-2" />
            </div>
        @endcanany
        @canany(['view', 'update'], [\App\Settings\FortifySettings::class, 'update_profile_enabled'])
            <!-- Profile Updating Enabled -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="update_profile_enabled" value="{{ __('Profile Updating Enabled') }}" />
                @can('update', [\App\Settings\FortifySettings::class, 'update_profile_enabled'])
                    <x-jet-checkbox id="update_profile_enabled" class="mt-3" wire:model.defer="update_profile_enabled" />
                @else
                    <x-jet-checkbox id="update_profile_enabled" class="mt-3" wire:model.defer="update_profile_enabled" disabled />
                @endcan
                <x-jet-input-error for="update_profile_enabled" class="mt-2" />
            </div>
        @endcanany
        @canany(['view', 'update'], [\App\Settings\FortifySettings::class, 'two_factor_authentication_enabled'])
            <!-- 2FA Enabled -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="two_factor_authentication_enabled" value="{{ __('2FA Enabled') }}" />
                @can('update', [\App\Settings\FortifySettings::class, 'two_factor_authentication_enabled'])
                    <x-jet-checkbox id="two_factor_authentication_enabled" class="mt-3" wire:model.defer="two_factor_authentication_enabled" />
                @else
                    <x-jet-checkbox id="two_factor_authentication_enabled" class="mt-3" wire:model.defer="two_factor_authentication_enabled" disabled />
                @endcan
                <x-jet-input-error for="two_factor_authentication_enabled" class="mt-2" />
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
