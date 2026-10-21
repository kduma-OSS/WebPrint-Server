<x-form-section submit="save">

    <x-slot name="title">
        {{ __('client-applications.update.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('client-applications.update.description') }}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('client-applications.name_label') }}" />
            @can('update', $app)
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
            @else
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" disabled readonly/>
            @endcan
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- URL -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="url" value="{{ __('client-applications.url_label') }}" />
            @can('update', $app)
                <x-input id="url" type="text" class="mt-1 block w-full" wire:model="url" />
            @else
                <x-input id="url" type="text" class="mt-1 block w-full" wire:model="url" disabled readonly/>
            @endcan
            <x-input-error for="url" class="mt-2" />
        </div>

        <div class="col-span-6">
            <x-label for="printers" value="{{ __('client-applications.printers_label') }}" />

            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($this->available_printers as $printer)
                    <label class="flex items-center">
                        @can('update', $app)
                            <x-checkbox wire:model="printers" :value="$printer->ulid"/>
                        @else
                            <x-checkbox wire:model="printers" :value="$printer->ulid" disabled/>
                        @endcan
                        <span class="ml-2 text-sm text-gray-600">{{ $printer->name }} @ {{ $printer->Server->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </x-slot>

    @can('update', $app)
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
