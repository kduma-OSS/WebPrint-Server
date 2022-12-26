<x-jet-form-section submit="save">

    <x-slot name="title">
        {{ __('print-servers.create.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('print-servers.create.description') }}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('print-servers.name_label') }}" />
            <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" />
            <x-jet-input-error for="name" class="mt-2" />
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
