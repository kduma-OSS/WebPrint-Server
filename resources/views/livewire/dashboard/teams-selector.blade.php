<div wire:poll.visible="refresh" wire:init="refresh">
    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('dashboard.teams.heading') }}</h3>
    @if($teams !== null)
        <x-card-collection class="mt-5" grid="4">
            @foreach($teams as $team)
                <x-teams.card :team="$team" />
            @endforeach

            @can('create', \App\Models\Team::class)
                <x-teams.create-card />
            @endcan
        </x-card-collection>
    @else
        <x-loading-card class="mt-5"/>
    @endif
</div>
