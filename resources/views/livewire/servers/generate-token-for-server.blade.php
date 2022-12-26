<x-jet-action-section>
    <x-slot name="title">
        {{ __('print-servers.token.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('print-servers.token.description') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('print-servers.token.message') }}
        </div>

        <div class="mt-5">
            <x-jet-danger-button wire:click="generateToken" wire:loading.attr="disabled">
                {{ __('print-servers.token.button') }}
            </x-jet-danger-button>
        </div>

        <!-- Token Value Modal -->
        <x-jet-dialog-modal wire:model="displayingToken">
            <x-slot name="title">
                {{ __('print-servers.token.modal-title') }}
            </x-slot>

            <x-slot name="content">
                <div>
                    {{ __('print-servers.token.modal-token') }}
                </div>

                <x-jet-input x-ref="plaintextToken" type="text" readonly :value="$plainTextToken"
                             class="my-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full"
                             autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                             @showing-token-modal.window="setTimeout(() => $refs.plaintextToken.select(), 250)"
                             @click="$refs.plaintextToken.select()"
                />

                <div>
                    {{ __('print-servers.token.modal-env') }}
                </div>

                <x-jet-textarea x-ref="envVariables" type="text" readonly :value="$envVariables" :rows="\Illuminate\Support\Str::linesCount($envVariables ?? '')+1"
                             class="my-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full"
                             autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                             @click="$refs.envVariables.select()"
                />

                <div>
                    {{ __('print-servers.token.modal-docker') }}
                </div>

                <x-jet-textarea x-ref="dockerCommand" type="text" readonly :value="$dockerCommand" :rows="\Illuminate\Support\Str::linesCount($dockerCommand ?? '')+2"
                             class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full"
                             autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                             @click="$refs.dockerCommand.select()"
                />
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$set('displayingToken', false)" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-jet-secondary-button>
            </x-slot>
        </x-jet-dialog-modal>

        <!-- Overwrite Token Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingApiTokenOverwrite">
            <x-slot name="title">
                {{ __('print-servers.token.overwrite_modal.title') }}
            </x-slot>

            <x-slot name="content">
                {{ __('print-servers.token.overwrite_modal.content') }}
                @if($tokenLastUsed)
                    {{ __('print-servers.token.overwrite_modal.content_last_used', ['last_used' => $tokenLastUsed]) }}
                @endif
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingApiTokenOverwrite')" wire:loading.attr="disabled">
                    {{ __('common.buttons.cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-3" wire:click="generateToken(true)" wire:loading.attr="disabled">
                    {{ __('print-servers.token.overwrite_modal.button') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

    </x-slot>
</x-jet-action-section>
