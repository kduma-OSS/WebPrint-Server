<x-form-section submit="save">

    <x-slot name="title">
        {{ __('printers.create.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('printers.create.description') }}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('printers.name_label') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
            <x-input-error for="name" class="mt-2" />
        </div>
        <!-- URI -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="uri" value="{{ __('printers.uri_label') }}" />
            <x-input id="uri" type="text" class="mt-1 block w-full" wire:model="uri" />
            <x-input-error for="uri" class="mt-2" />
        </div>
        <!-- Languages -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="languages" value="{{ __('printers.languages_label') }}" />
            <x-input id="languages" type="text" class="mt-1 block w-full" wire:model="languages" />
            <x-input-error for="languages" class="mt-2" />
        </div>
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
