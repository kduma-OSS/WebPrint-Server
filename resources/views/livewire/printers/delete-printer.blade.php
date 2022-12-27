<x-jet-action-section>
    <x-slot name="title">
        {{ __('printers.delete.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('printers.delete.description') }}
    </x-slot>

    <x-slot name="content">
        @if(!$this->can_be_deleted)
            <div class="max-w-xl text-sm text-gray-600">
                {{ __('printers.delete.message_disabled') }}
            </div>

            <div class="mt-5">
                <x-jet-danger-button disabled>
                    {{ __('printers.delete.button') }}
                </x-jet-danger-button>
            </div>
        @else
            <div class="max-w-xl text-sm text-gray-600">
                {{ __('printers.delete.message') }}
            </div>

            <div class="mt-5">
                <x-jet-danger-button wire:click="$toggle('confirmingPrinterDeletion')" wire:loading.attr="disabled">
                    {{ __('printers.delete.button') }}
                </x-jet-danger-button>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <x-jet-confirmation-modal wire:model="confirmingPrinterDeletion">
                <x-slot name="title">
                    {{ __('printers.delete.modal.title') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('printers.delete.modal.content') }}
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('confirmingPrinterDeletion')" wire:loading.attr="disabled">
                        {{ __('common.buttons.cancel') }}
                    </x-jet-secondary-button>

                    <x-jet-danger-button class="ml-3" wire:click="deletePrinter" wire:loading.attr="disabled">
                        {{ __('printers.delete.button') }}
                    </x-jet-danger-button>
                </x-slot>
            </x-jet-confirmation-modal>
        @endif
    </x-slot>
</x-jet-action-section>
