<x-dialog-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('print-dialog.errors.ip-error.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>{{ __('print-dialog.errors.ip-error.message_1') }}</p>
                    <p>{{ __('print-dialog.errors.ip-error.message_2', ['ip' => request()->ip()]) }}</p>

                    @if($dialog->redirect_url)
                        <div class="mt-5">
                            <a href="{{ $dialog->redirect_url }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                                {{ __('print-dialog.go-back-button') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-dialog-layout>
