<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('Team Settings') }}
        </x-layout.header>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('teams.update-team-name-form', ['team' => $team])

            @livewire('teams.team-member-manager', ['team' => $team])

            @if (Gate::check('delete', $team) && ! $team->personal_team)
                <x-jet-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('teams.delete-team-form-with-confirmation', ['team' => $team])
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
