<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('navigation.dashboard') }}
        </x-layout.header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('viewDashboard', [auth()->user()->currentTeam, 'teams'])
                <livewire:dashboard.teams-selector :user="auth()->user()" />
            @endif

            @can('viewDashboard', [auth()->user()->currentTeam, 'stats'])
                <livewire:dashboard.stats :team="auth()->user()->currentTeam" />
            @endif

            @can('viewDashboard', [auth()->user()->currentTeam, 'servers'])
                <livewire:dashboard.print-servers :team="auth()->user()->currentTeam" />
            @endif

            @can('viewDashboard', [auth()->user()->currentTeam, 'jobs'])
                <livewire:dashboard.pending-jobs :team="auth()->user()->currentTeam" />
                <livewire:dashboard.failed-jobs :team="auth()->user()->currentTeam" />
            @endif
        </div>
    </div>
</x-app-layout>
