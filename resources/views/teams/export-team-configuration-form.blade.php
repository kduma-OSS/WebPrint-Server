<x-jet-action-section>
    <x-slot name="title">
        {{ __('Export Team Configuration') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Download team print infrastructure configuration.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Export the complete print infrastructure configuration for this team, including print servers, printers, client applications, and their API tokens. This file can be used to migrate to a new WebPrint Server instance.') }}
        </div>

        <div class="mt-5">
            <x-jet-confirms-password wire:then="confirmExport">
                <x-jet-button wire:loading.attr="disabled">
                    {{ __('Export Configuration') }}
                </x-jet-button>
            </x-jet-confirms-password>
        </div>

        <!-- Export Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingExport">
            <x-slot name="title">
                {{ __('Export Team Configuration') }}
            </x-slot>

            <x-slot name="content">
                <div class="space-y-4">
                    <p>{{ __('You are about to export the complete print infrastructure configuration for this team.') }}</p>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">{{ __('Security Notice') }}</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>{{ __('The export file contains hashed API tokens. Store this file securely as it can be used to restore access to your print infrastructure.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600">{{ __('The export includes:') }}</p>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li>{{ __('Print servers and their configurations') }}</li>
                        <li>{{ __('Printers with PPD options and settings') }}</li>
                        <li>{{ __('Client applications and printer access mappings') }}</li>
                        <li>{{ __('API tokens (hashed)') }}</li>
                    </ul>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingExport')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-button class="ml-3" wire:click="export" wire:loading.attr="disabled">
                    {{ __('Download Export') }}
                </x-jet-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </x-slot>
</x-jet-action-section>
