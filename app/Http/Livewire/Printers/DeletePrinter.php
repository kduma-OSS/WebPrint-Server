<?php

namespace App\Http\Livewire\Printers;

use App\Actions\Printers\DeletePrinterAction;
use App\Models\Printer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class DeletePrinter extends Component
{
    use AuthorizesRequests;

    /**
     * @var Printer
     */
    public Printer $printer;

    public $confirmingPrinterDeletion = false;

    public function getCanBeDeletedProperty(): bool
    {
        return true;
    }

    public function deletePrinter(DeletePrinterAction $action)
    {
        $this->authorize('update', $this->printer);

        $server = $this->printer->server;

        $action->handle($this->printer);

        return redirect()->route('web-print.servers.printers.index', $server);
    }

    public function mount(Printer $printer)
    {
        $this->printer = $printer;
    }

    public function render()
    {
        return view('livewire.printers.delete-printer');
    }
}
