<x-jet-form-section submit="save">

    <x-slot name="title">
        {{ __('client-applications.update.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('client-applications.update.description') }}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('client-applications.name_label') }}" />
            @can('update', $app)
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" />
            @else
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name" disabled readonly/>
            @endcan
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <!-- URL -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="url" value="{{ __('client-applications.url_label') }}" />
            @can('update', $app)
                <x-jet-input id="url" type="text" class="mt-1 block w-full" wire:model.defer="url" />
            @else
                <x-jet-input id="url" type="text" class="mt-1 block w-full" wire:model.defer="url" disabled readonly/>
            @endcan
            <x-jet-input-error for="url" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-jet-label for="printers" value="{{ __('client-applications.printers_label') }}" />

            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($this->available_printers as $printer)
                    <label class="flex items-center">
                        @can('update', $app)
                            <x-jet-checkbox wire:model.defer="printers" :value="$printer->ulid"/>
                        @else
                            <x-jet-checkbox wire:model.defer="printers" :value="$printer->ulid" disabled/>
                        @endcan
                        <span class="ml-2 text-sm text-gray-600">{{ $printer->name }} @ {{ $printer->Server->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </x-slot>

    @can('update', $app)
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
