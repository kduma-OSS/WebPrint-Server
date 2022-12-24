<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(!$dialog->is_active)
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        @switch($dialog->status)
                                Print job is Canceled
                            @case(\App\Models\Enums\PrintDialogStatusEnum::Cancelled)
                            @break
                                Print job is already sent for printing
                            @case(\App\Models\Enums\PrintDialogStatusEnum::Sent)
                            @break
                            @default
                                Print job not active
                        @endswitch
                    </h3>
                    <div class="mt-2 max-w-xl text-sm text-gray-500">
                        <p>
                            @switch($dialog->status)
                                @case(\App\Models\Enums\PrintDialogStatusEnum::Cancelled)
                                    This print job was cancelled.
                                    @break
                                @case(\App\Models\Enums\PrintDialogStatusEnum::Sent)
                                    This print job has already been sent to printer.
                                    @break
                                @default
                                    This print job is not active for printing. It has already been sent to printer or cancelled.
                            @endswitch
                        </p>
                        @if($dialog->JobPromise->PrintJob)
                            <p class="pt-3" wire:poll>
                                Status:
                                @switch($dialog->JobPromise->PrintJob->status)
                                    @case(\App\Models\Enums\PrintJobStatusEnum::New)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                      Waiting in print queue
                                    </span>
                                    @break
                                    @case(\App\Models\Enums\PrintJobStatusEnum::Printing)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                      Currently processing
                                    </span>
                                    @break
                                    @case(\App\Models\Enums\PrintJobStatusEnum::Finished)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                      Job sent to printer
                                    </span>
                                    @break
                                    @case(\App\Models\Enums\PrintJobStatusEnum::Failed)
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                      Printing Failed{{ $dialog->JobPromise->PrintJob->status_message ? ': ' : '' }} <i>{{ $dialog->JobPromise->PrintJob->status_message }}</i>
                                    </span>
                                    @break
                                @endswitch
                            </p>
                        @endif
                    </div>
                    @if($dialog->redirect_url)
                        <div class="mt-5">
                            <button type="button" wire:click="cancel" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                                Go Back
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">


                <div class="p-6 bg-white border-b border-gray-200">
                    <form class="space-y-8 divide-y divide-gray-200">
                        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                            <div>
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Print Job: <strong>{{ $dialog->JobPromise->name }}</strong>
                                    </h3>
{{--                                        <p class="mt-1 max-w-2xl text-sm text-gray-500">--}}
{{--                                            This information will be displayed publicly so be careful what you share.--}}
{{--                                        </p>--}}
                                </div>

                                <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                        <label for="printer" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                            Printer
                                        </label>
                                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                                            @if($selected_printer == null || $view == 'set_printer')
                                                <fieldset>
                                                    <legend class="sr-only">
                                                        Select Printer
                                                    </legend>

                                                    <div class="bg-white rounded-md -space-y-px">
                                                        @forelse($dialog->JobPromise->AvailablePrinters as $printer)
                                                            <div class="relative border {{ $loop->first ? 'rounded-tl-md rounded-tr-md' : '' }} {{ $loop->last ? 'rounded-bl-md rounded-br-md' : '' }} p-4 flex {{ $printer->ulid == $selected_printer_ulid ? 'bg-indigo-50 border-indigo-200 z-10' : 'border-gray-200' }}">
                                                                <div class="flex items-center h-5">
                                                                    <input id="printer-{{ $printer->ulid }}" name="printer" wire:model="selected_printer_ulid" wire:click="goToMainView" value="{{ $printer->ulid }}" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 cursor-pointer border-gray-300" {{ $printer->ulid == $selected_printer_ulid ? 'checked' : '' }}>
                                                                </div>
                                                                <label for="printer-{{ $printer->ulid }}" class="ml-3 flex flex-col cursor-pointer">
                                                                    <span class="block text-sm font-medium {{ $printer->ulid == $selected_printer_ulid ? 'text-indigo-900' : 'text-gray-900' }}">
                                                                      {{ $printer->name }}
                                                                    </span>
                                                                    @if($printer->location)
                                                                        <span class="block text-sm {{ $printer->ulid == $selected_printer_ulid ? 'text-indigo-700' : 'text-gray-500' }}">
                                                                          {{ $printer->location }}
                                                                        </span>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        @empty
                                                            No printers available
                                                        @endforelse
                                                    </div>
                                                </fieldset>
                                            @else
                                                <fieldset>
                                                    <div class="bg-white rounded-md -space-y-px">
                                                        @if($selected_printer !== null)
                                                            <div class="relative border rounded-tl-md rounded-tr-md rounded-bl-md rounded-br-md p-4 flex border-gray-200">
                                                                <a wire:click="goToSetPrinterView" class="ml-3 flex flex-col cursor-pointer">
                                                                    <span class="block text-sm font-medium text-gray-900">
                                                                      {{ $selected_printer->name }}
                                                                    </span>
                                                                    @if($selected_printer->location)
                                                                        <span class="block text-sm text-gray-500">
                                                                          {{ $selected_printer->location }}
                                                                        </span>
                                                                    @endif
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </fieldset>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($dialog->JobPromise->meta)
                                    <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label for="options" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                Job Metadata
                                            </label>
                                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                                <!-- This example requires Tailwind CSS v2.0+ -->
                                                    <div class="flex flex-col">
                                                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                                                <div class="border overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                                                    <table class="min-w-full divide-y divide-gray-200">
                                                                        <thead class="bg-gray-50">
                                                                        <tr>
                                                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                                Key
                                                                            </th>
                                                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                                Value
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                                        @foreach($dialog->JobPromise->meta as $key => $val)
                                                                            <tr>
                                                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                                                    {{ $key }}
                                                                                </td>
                                                                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                                    {{ $val }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($dialog->JobPromise->type == 'ppd' && $selected_printer != null && $selected_printer->ppd_options && $view != 'set_options')
                                    <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label for="options" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                Printer Settings
                                            </label>
                                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                                <fieldset>
                                                    <div class="bg-white rounded-md -space-y-px">
                                                        <div class="relative border rounded-tl-md rounded-tr-md rounded-bl-md rounded-br-md p-4 flex border-gray-200">
                                                            <a wire:click="goToSetOptionsView" class="ml-3 flex flex-col cursor-pointer">
                                                                <span class="block text-sm font-medium text-gray-900">
                                                                    Customize Print Options
                                                                </span>
                                                                <span class="block text-sm text-gray-500">
                                                                    {{ !$this->non_defaults->count() ? 'Printer Defaults' : $this->non_defaults->map(fn($value, $name) => sprintf("%s: %s",$name,$value))->implode('; ') }}
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($dialog->JobPromise->type == 'ppd' && $selected_printer != null && $selected_printer->ppd_options && $view == 'set_options')

                                @foreach($this->grouped_options as $group)
                                    <div class="pt-8 space-y-6 sm:pt-10 sm:space-y-5">
                                        <div>
                                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                {{ $group['name'] }}
                                            </h3>
{{--                                                <p class="mt-1 max-w-2xl text-sm text-gray-500">--}}
{{--                                                    Use a permanent address where you can receive mail.--}}
{{--                                                </p>--}}
                                        </div>
                                        <div class="space-y-6 sm:space-y-5">

                                            @foreach($group['options'] as $option)
                                                <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                    <label for="{{ $option['key'] }}" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                                        {{ $option['name'] }}
                                                    </label>
                                                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                                                        <select id="{{ $option['key'] }}" name="{{ $option['key'] }}" wire:model="ppd_options.{{ $option['key'] }}" class="max-w-lg block focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
                                                            @foreach($option['values'] as $value)
                                                                <option value="{{ $value['key'] }}">{{ $value['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            @endif

{{--                                <div class="divide-y divide-gray-200 pt-8 space-y-6 sm:pt-10 sm:space-y-5">--}}
{{--                                    <div>--}}
{{--                                        <h3 class="text-lg leading-6 font-medium text-gray-900">--}}
{{--                                            Notifications--}}
{{--                                        </h3>--}}
{{--                                        <p class="mt-1 max-w-2xl text-sm text-gray-500">--}}
{{--                                            We'll always let you know about important changes, but you pick what else you want to hear about.--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="space-y-6 sm:space-y-5 divide-y divide-gray-200">--}}
{{--                                        <div class="pt-6 sm:pt-5">--}}
{{--                                            <div role="group" aria-labelledby="label-email">--}}
{{--                                                <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">--}}
{{--                                                    <div>--}}
{{--                                                        <div class="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700" id="label-email">--}}
{{--                                                            By Email--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="mt-4 sm:mt-0 sm:col-span-2">--}}
{{--                                                        <div class="max-w-lg space-y-4">--}}
{{--                                                            <div class="relative flex items-start">--}}
{{--                                                                <div class="flex items-center h-5">--}}
{{--                                                                    <input id="comments" name="comments" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">--}}
{{--                                                                </div>--}}
{{--                                                                <div class="ml-3 text-sm">--}}
{{--                                                                    <label for="comments" class="font-medium text-gray-700">Comments</label>--}}
{{--                                                                    <p class="text-gray-500">Get notified when someones posts a comment on a posting.</p>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div>--}}
{{--                                                                <div class="relative flex items-start">--}}
{{--                                                                    <div class="flex items-center h-5">--}}
{{--                                                                        <input id="candidates" name="candidates" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="ml-3 text-sm">--}}
{{--                                                                        <label for="candidates" class="font-medium text-gray-700">Candidates</label>--}}
{{--                                                                        <p class="text-gray-500">Get notified when a candidate applies for a job.</p>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                            <div>--}}
{{--                                                                <div class="relative flex items-start">--}}
{{--                                                                    <div class="flex items-center h-5">--}}
{{--                                                                        <input id="offers" name="offers" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="ml-3 text-sm">--}}
{{--                                                                        <label for="offers" class="font-medium text-gray-700">Offers</label>--}}
{{--                                                                        <p class="text-gray-500">Get notified when a candidate accepts or rejects an offer.</p>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="pt-6 sm:pt-5">--}}
{{--                                            <div role="group" aria-labelledby="label-notifications">--}}
{{--                                                <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">--}}
{{--                                                    <div>--}}
{{--                                                        <div class="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700" id="label-notifications">--}}
{{--                                                            Push Notifications--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="sm:col-span-2">--}}
{{--                                                        <div class="max-w-lg">--}}
{{--                                                            <p class="text-sm text-gray-500">These are delivered via SMS to your mobile phone.</p>--}}
{{--                                                            <div class="mt-4 space-y-4">--}}
{{--                                                                <div class="flex items-center">--}}
{{--                                                                    <input id="push_everything" name="push_notifications" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">--}}
{{--                                                                    <label for="push_everything" class="ml-3 block text-sm font-medium text-gray-700">--}}
{{--                                                                        Everything--}}
{{--                                                                    </label>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="flex items-center">--}}
{{--                                                                    <input id="push_email" name="push_notifications" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">--}}
{{--                                                                    <label for="push_email" class="ml-3 block text-sm font-medium text-gray-700">--}}
{{--                                                                        Same as email--}}
{{--                                                                    </label>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="flex items-center">--}}
{{--                                                                    <input id="push_nothing" name="push_notifications" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">--}}
{{--                                                                    <label for="push_nothing" class="ml-3 block text-sm font-medium text-gray-700">--}}
{{--                                                                        No push notifications--}}
{{--                                                                    </label>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                        </div>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <button type="button" wire:click="cancel" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                                <button type="button" wire:click="sendToPrint" class="{{ $selected_printer == null || !$dialog->is_active ? 'cursor-not-allowed bg-gray-600' : 'bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' }} ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white ">
                                    Print
                                </button>
                            </div>
                        </div>
                    </form>









                </div>
            </div>
        @endif
    </div>
</div>
