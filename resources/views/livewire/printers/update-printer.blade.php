<x-form-section submit="save">

    <x-slot name="title">
        {{ __('printers.update.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('printers.update.description') }}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('printers.name_label') }}" />
            @can('update', $printer)
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
            @else
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" disabled readonly/>
            @endcan
            <x-input-error for="name" class="mt-2" />
        </div>
        <!-- Location -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="location" value="{{ __('printers.location_label') }}" />
            @can('update', $printer)
                <x-input id="location" type="text" class="mt-1 block w-full" wire:model="location" />
            @else
                <x-input id="location" type="text" class="mt-1 block w-full" wire:model="location" disabled readonly/>
            @endcan
            <x-input-error for="location" class="mt-2" />
        </div>
        <!-- URI -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="uri" value="{{ __('printers.uri_label') }}" />
            @can('update', $printer)
                <x-input id="uri" type="text" class="mt-1 block w-full" wire:model="uri" />
            @else
                <x-input id="uri" type="text" class="mt-1 block w-full" wire:model="uri" disabled readonly/>
            @endcan
            <x-input-error for="uri" class="mt-2" />
        </div>
        <!-- Languages -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="languages" value="{{ __('printers.languages_label') }}" />
            @can('update', $printer)
                <x-input id="languages" type="text" class="mt-1 block w-full" wire:model="languages" />
            @else
                <x-input id="languages" type="text" class="mt-1 block w-full" wire:model="languages" disabled readonly/>
            @endcan
            <x-input-error for="languages" class="mt-2" />
        </div>
        <!-- Enabled -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="enabled" value="{{ __('printers.enabled_label') }}" />
            @can('update', $printer)
                <x-checkbox id="enabled" type="text" class="mt-1 block" wire:model="enabled" />
            @else
                <x-checkbox id="enabled" type="text" class="mt-1 block" wire:model="enabled" disabled readonly/>
            @endcan
            <x-input-error for="enabled" class="mt-2" />
        </div>
        <!-- PPD -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="ppd" value="{{ __('printers.ppd_label') }}" />
            @can('update', $printer)
                <x-checkbox id="ppd" type="text" class="mt-1 block" wire:model.live="ppd" />
            @else
                <x-checkbox id="ppd" type="text" class="mt-1 block" wire:model.live="ppd" disabled readonly/>
            @endcan
            <x-input-error for="ppd" class="mt-2" />
        </div>
        @if($ppd)
            <!-- PPD Options -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="options" value="{{ __('printers.options_label') }}" />
                @can('update', $printer)
                    <x-textarea id="options" rows="{{ min(10, \Illuminate\Support\Str::linesCount($options ?? '') + 1) }}" class="mt-1 block w-full" wire:model="options" />
                @else
                    <x-textarea id="options" rows="{{ min(10, \Illuminate\Support\Str::linesCount($options ?? '') + 1) }}" class="mt-1 block w-full" wire:model="options" disabled readonly/>
                @endcan
                <x-input-error for="options" class="mt-2" />
            </div>
        @endif
    </x-slot>

    @can('update', $printer)
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
