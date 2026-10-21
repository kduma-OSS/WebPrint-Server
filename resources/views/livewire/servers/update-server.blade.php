<x-form-section submit="save">

    <x-slot name="title">
        {{ __('print-servers.update.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('print-servers.update.description') }}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('print-servers.name_label') }}" />
            @can('update', $server)
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
            @else
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" disabled readonly/>
            @endcan
            <x-input-error for="name" class="mt-2" />
        </div>
        <!-- Location -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="location" value="{{ __('print-servers.location_label') }}" />
            @can('update', $server)
                <x-input id="location" type="text" class="mt-1 block w-full" wire:model="location" />
            @else
                <x-input id="location" type="text" class="mt-1 block w-full" wire:model="location" disabled readonly/>
            @endcan
            <x-input-error for="location" class="mt-2" />
        </div>
    </x-slot>

    @can('update', $server)
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button>
                {{ __('Save') }}
            </x-button>
        </x-slot>
    @endcan
</x-form-section>
