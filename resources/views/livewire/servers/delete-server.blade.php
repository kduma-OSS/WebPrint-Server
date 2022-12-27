<x-jet-action-section>
    <x-slot name="title">
        {{ __('print-servers.delete.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('print-servers.delete.description') }}
    </x-slot>

    <x-slot name="content">
        @if(!$this->can_be_deleted)
            <div class="max-w-xl text-sm text-gray-600">
                {{ __('print-servers.delete.message_disabled') }}
            </div>

            <div class="mt-5">
                <x-jet-danger-button disabled>
                    {{ __('print-servers.delete.button') }}
                </x-jet-danger-button>
            </div>
        @else
            <div class="max-w-xl text-sm text-gray-600">
                {{ __('print-servers.delete.message') }}
            </div>

            <div class="mt-5">
                <x-jet-danger-button wire:click="$toggle('confirmingServerDeletion')" wire:loading.attr="disabled">
                    {{ __('print-servers.delete.button') }}
                </x-jet-danger-button>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <x-jet-confirmation-modal wire:model="confirmingServerDeletion">
                <x-slot name="title">
                    {{ __('print-servers.delete.modal.title') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('print-servers.delete.modal.content') }}
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('confirmingServerDeletion')" wire:loading.attr="disabled">
                        {{ __('common.buttons.cancel') }}
                    </x-jet-secondary-button>

                    <x-jet-danger-button class="ml-3" wire:click="deleteServer" wire:loading.attr="disabled">
                        {{ __('print-servers.delete.button') }}
                    </x-jet-danger-button>
                </x-slot>
            </x-jet-confirmation-modal>
        @endif
    </x-slot>
</x-jet-action-section>
