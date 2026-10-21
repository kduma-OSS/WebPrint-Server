<x-action-section>
    <x-slot name="title">
        {{ __('client-applications.delete.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('client-applications.delete.description') }}
    </x-slot>

    <x-slot name="content">
        @if(!$this->can_be_deleted)
            <div class="max-w-xl text-sm text-gray-600">
                {{ __('client-applications.delete.message_disabled') }}
            </div>

            <div class="mt-5">
                <x-danger-button disabled>
                    {{ __('client-applications.delete.button') }}
                </x-danger-button>
            </div>
        @else
            <div class="max-w-xl text-sm text-gray-600">
                {{ __('client-applications.delete.message') }}
            </div>

            <div class="mt-5">
                <x-danger-button wire:click="$toggle('confirmingAppDeletion')" wire:loading.attr="disabled">
                    {{ __('client-applications.delete.button') }}
                </x-danger-button>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <x-confirmation-modal wire:model="confirmingAppDeletion">
                <x-slot name="title">
                    {{ __('client-applications.delete.modal.title') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('client-applications.delete.modal.content') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$toggle('confirmingAppDeletion')" wire:loading.attr="disabled">
                        {{ __('common.buttons.cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ml-3" wire:click="deleteApp" wire:loading.attr="disabled">
                        {{ __('client-applications.delete.button') }}
                    </x-danger-button>
                </x-slot>
            </x-confirmation-modal>
        @endif
    </x-slot>
</x-action-section>
