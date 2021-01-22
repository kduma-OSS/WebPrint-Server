<x-dialog-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Print: <strong>{{ $dialog->JobPromise->name }}</strong>
        </h2>
    </x-slot>

    <livewire:print-dialog :dialog="$dialog" />

</x-dialog-layout>
