<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('navigation.dashboard') }}
        </x-layout.header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('viewDashboard', [auth()->user()->currentTeam, 'stats'])
                <livewire:dashboard.stats :team="auth()->user()->currentTeam" />
            @endif
        </div>
    </div>
</x-app-layout>
