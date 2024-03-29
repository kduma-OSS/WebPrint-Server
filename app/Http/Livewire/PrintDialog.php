<?php

namespace App\Http\Livewire;

use App\Actions\Promises\CheckPromiseAbilityToBePrintedAction;
use App\Actions\Promises\ConvertPromiseToJobAction;
use App\Models\Enums\PrintDialogStatusEnum;
use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\PrintDialog as PrintDialogModel;
use App\Models\Printer;
use Illuminate\Support\Str;
use League\Uri\Uri;
use Livewire\Component;

class PrintDialog extends Component
{
    public PrintDialogModel $dialog;

    public ?Printer $selected_printer = null;

    public ?string $selected_printer_ulid = null;

    public array $ppd_options = [];

    public string $view = 'main';

    public function mount(PrintDialogModel $dialog): void
    {
        $this->dialog = $dialog;

        if ($this->dialog->JobPromise->Printer !== null) {
            $this->selected_printer = $this->dialog->JobPromise->Printer;
            $this->selected_printer_ulid = $this->selected_printer->ulid;
            $this->ppd_options = $this->getDefaultForPrinter($this->selected_printer, $this->dialog->JobPromise->ppd_options ?? []);
        }
    }

    public function updated($propertyName): void
    {
        if ($propertyName == 'selected_printer_ulid') {
            $printer = $this->dialog->JobPromise->AvailablePrinters()->where('ulid', $this->selected_printer_ulid)->first();
            if ($printer === null) {
                $this->selected_printer_ulid = null;
                $this->selected_printer = null;
                $this->ppd_options = [];
            } else {
                if ($this->selected_printer_ulid != $this->selected_printer?->ulid) {
                    $this->ppd_options = $this->getDefaultForPrinter($printer);
                }

                $this->selected_printer_ulid = $printer->ulid;
                $this->selected_printer = $printer;

                if ($this->view == 'set_printer') {
                    $this->view = 'main';
                }
            }
        } elseif (Str::startsWith($propertyName, 'ppd_options.')) {
            $option = Str::after($propertyName, 'ppd_options.');

            $p = collect($this->selected_printer->ppd_options)
                ->firstWhere('key', $option);

            if ($p === null) {
                if (isset($this->ppd_options[$option])) {
                    unset($this->ppd_options[$option]);
                }
            } else {
                $v = collect($p['values'])
                    ->firstWhere('key', $this->ppd_options[$option]);

                if ($v === null) {
                    $this->ppd_options[$option] = $p['default'];
                }
            }
        }

//                $this->validateOnly($propertyName);
    }

    public function cancel()
    {
        $reason = $this->dialog->is_active ? 'cancelled' : 'inactive';

        if ($this->dialog->status == PrintDialogStatusEnum::New) {
            $this->dialog->status = PrintDialogStatusEnum::Cancelled;
            $this->dialog->save();
        }

        if ($this->dialog->JobPromise->status == PrintJobPromiseStatusEnum::New) {
            $this->dialog->JobPromise->status = PrintJobPromiseStatusEnum::Cancelled;
            $this->dialog->JobPromise->save();
        }

        if ($this->dialog->redirect_url) {
            $uri = Uri::createFromString($this->dialog->redirect_url);

            if ($uri->getQuery() === null) {
                $uri = $uri->withQuery(http_build_query([
                    'dialog' => [
                        'ulid' => $this->dialog->ulid,
                        'reason' => $reason,
                    ],
                ]));
            }

            return redirect()->to($uri);
        }
    }

    public function sendToPrint(
        CheckPromiseAbilityToBePrintedAction $checkPromiseAbilityToBePrintedAction,
        ConvertPromiseToJobAction $convertPromiseToJobAction,
    ) {
        if (! $this->dialog->is_active) {
            return null;
        }

        if ($this->selected_printer === null) {
            return null;
        }

        $this->dialog->status = 'sent';
        $this->dialog->save();

        $promise = $this->dialog->JobPromise;
        $promise->ppd_options = $this->ppd_options;
        $promise->printer_id = $this->selected_printer->id;

        if ($this->dialog->auto_print) {
            $promise->status = 'ready';
            $promise->save();
            if ($checkPromiseAbilityToBePrintedAction->handle($promise)) {
                $convertPromiseToJobAction->handle($promise);
            }
        }

        if ($this->dialog->redirect_url) {
            $uri = Uri::createFromString($this->dialog->redirect_url);

            if ($uri->getQuery() === null) {
                $uri = $uri->withQuery(http_build_query([
                    'dialog' => [
                        'ulid' => $this->dialog->ulid,
                        'reason' => 'success',
                    ],
                ]));
            }

            return redirect()->to($uri);
        }
    }

    public function getGroupedOptionsProperty()
    {
        return collect($this->selected_printer->ppd_options)
            ->filter(fn ($element): mixed => $element['enabled'])
            ->groupBy('group_key')
            ->sortBy(fn ($group, $key): mixed => $group->first()['order'])
            ->map->sortBy('order')
            ->mapWithKeys(function ($group, $key): array {
                return [
                    $group->first()['group_key'] => [
                        'key' => $group->first()['group_key'],
                        'name' => $group->first()['group_name'],
                        'options' => $group
                            ->mapWithKeys(function ($element, $key): array {
                                return [
                                    $element['key'] => [
                                        'key' => $element['key'],
                                        'name' => $element['name'],
                                        'values' => collect($element['values'])
                                            ->filter(fn ($element): mixed => $element['enabled'])
                                            ->sortBy('order')
                                            ->mapWithKeys(fn ($option, $key): array => [
                                                $option['key'] => [
                                                    'key' => $option['key'],
                                                    'name' => $option['name'],
                                                ],
                                            ]),
                                    ],
                                ];
                            }),
                    ],
                ];
            });
    }

    public function getNonDefaultsProperty()
    {
        return collect($this->selected_printer->ppd_options)
            ->sortBy('order')
            ->filter(fn ($element): mixed => $element['enabled'])
            ->filter(fn ($option): bool => ($this->ppd_options[$option['key']] ?? null) != $option['default'])
            ->mapWithKeys(fn ($option): array => [$option['name'] => collect($option['values'])->firstWhere('key', $this->ppd_options[$option['key']])['name']]);
    }

    public function goToSetPrinterView(): void
    {
        $this->view = 'set_printer';
    }

    public function goToMainView(): void
    {
        $this->view = 'main';
    }

    public function goToSetOptionsView(): void
    {
        $this->view = 'set_options';
    }

    public function getDefaultForPrinter(Printer $printer, array $merge = [])
    {
        return collect($printer->ppd_options)
            ->mapWithKeys(fn ($option, $key): array => [$option['key'] => $option['default']])
            ->merge($merge)
            ->toArray();
    }

    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function render()
    {
        return view('livewire.print-dialog');
    }
}
