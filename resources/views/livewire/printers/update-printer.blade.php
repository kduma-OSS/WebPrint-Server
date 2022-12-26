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
        <!-- URI -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="uri" value="{{ __('printers.uri_label') }}" />
            @can('update', $printer)
                <x-jet-input id="uri" type="text" class="mt-1 block w-full" wire:model.defer="uri" />
            @else
                <x-jet-input id="uri" type="text" class="mt-1 block w-full" wire:model.defer="uri" disabled readonly/>
            @endcan
            <x-jet-input-error for="uri" class="mt-2" />
        </div>
        <!-- Languages -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="languages" value="{{ __('printers.languages_label') }}" />
            @can('update', $printer)
                <x-jet-input id="languages" type="text" class="mt-1 block w-full" wire:model.defer="languages" />
            @else
                <x-jet-input id="languages" type="text" class="mt-1 block w-full" wire:model.defer="languages" disabled readonly/>
            @endcan
            <x-jet-input-error for="languages" class="mt-2" />
        </div>
        <!-- Enabled -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="enabled" value="{{ __('printers.enabled_label') }}" />
            @can('update', $printer)
                <x-jet-checkbox id="enabled" type="text" class="mt-1 block" wire:model.defer="enabled" />
            @else
                <x-jet-checkbox id="enabled" type="text" class="mt-1 block" wire:model.defer="enabled" disabled readonly/>
            @endcan
            <x-jet-input-error for="enabled" class="mt-2" />
        </div>
        <!-- PPD -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="ppd" value="{{ __('printers.ppd_label') }}" />
            @can('update', $printer)
                <x-jet-checkbox id="ppd" type="text" class="mt-1 block" wire:model="ppd" />
            @else
                <x-jet-checkbox id="ppd" type="text" class="mt-1 block" wire:model="ppd" disabled readonly/>
            @endcan
            <x-jet-input-error for="ppd" class="mt-2" />
        </div>
        @if($ppd)
            <!-- PPD Options -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="options" value="{{ __('printers.options_label') }}" />
                @can('update', $printer)
                    <x-jet-textarea id="options" rows="{{ min(10, \Illuminate\Support\Str::linesCount($options ?? '') + 1) }}" class="mt-1 block w-full" wire:model.defer="options" />
                @else
                    <x-jet-textarea id="options" rows="{{ min(10, \Illuminate\Support\Str::linesCount($options ?? '') + 1) }}" class="mt-1 block w-full" wire:model.defer="options" disabled readonly/>
                @endcan
                <x-jet-input-error for="options" class="mt-2" />
            </div>
        @endif
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
