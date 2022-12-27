<x-app-layout>
    <x-slot name="header">
        <x-layout.header>
            {{ __('navigation.dashboard') }}
        </x-layout.header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:dashboard.stats :team="auth()->user()->currentTeam" />
        </div>
    </div>
</x-app-layout>
