<x-jet-form-section submit="save">

    <x-slot name="title">
        {{ __('printers.update.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('printers.update.description') }}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('printers.name_label') }}" />
            @can('update', $printer)
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" />
            @else
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" disabled readonly/>
            @endcan
            <x-jet-input-error for="name" class="mt-2" />
        </div>
        <!-- Location -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="location" value="{{ __('printers.location_label') }}" />
            @can('update', $printer)
                <x-jet-input id="location" type="text" class="mt-1 block w-full" wire:model.defer="location" />
            @else
                <x-jet-input id="location" type="text" class="mt-1 block w-full" wire:model.defer="location" disabled readonly/>
            @endcan
            <x-jet-input-error for="location" class="mt-2" />
        </div>
    </x-slot>

    @can('update', $printer)
        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-jet-action-message>

            <x-jet-button>
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    @endcan
</x-jet-form-section>
