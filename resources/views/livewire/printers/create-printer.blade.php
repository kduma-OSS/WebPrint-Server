<x-jet-form-section submit="save">

    <x-slot name="title">
        {{ __('printers.create.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('printers.create.description') }}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('printers.name_label') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" />
            <x-jet-input-error for="name" class="mt-2" />
        </div>
        <!-- URI -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="uri" value="{{ __('printers.uri_label') }}" />
            <x-jet-input id="uri" type="text" class="mt-1 block w-full" wire:model.defer="uri" />
            <x-jet-input-error for="uri" class="mt-2" />
        </div>
        <!-- Languages -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="languages" value="{{ __('printers.languages_label') }}" />
            <x-jet-input id="languages" type="text" class="mt-1 block w-full" wire:model.defer="languages" />
            <x-jet-input-error for="languages" class="mt-2" />
        </div>
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
