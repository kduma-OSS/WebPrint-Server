<?php

namespace App\Http\Livewire\Printers;

use App\Actions\Printers\CreatePrinterAction;
use App\Models\Printer;
use App\Models\PrintServer;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreatePrinter extends Component
{
    use AuthorizesRequests;

    /**
     * @var PrintServer
     */
    public PrintServer $server;

    /**
     * @var string
     */
    public $name;

    protected $rules = [
        'name' => ['required', 'string', 'min:1', 'max:255'],
    ];

    public function mount(PrintServer $server)
    {
        $this->server = $server;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save(CreatePrinterAction $action)
    {
        $this->authorize('create', [Printer::class, $this->server]);
        $this->validate();

        $printer = $action->handle($this->server, $this->name);

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');

        return redirect()->route('web-print.printers.show', $printer);
    }

    public function render()
    {
        return view('livewire.printers.create-printer');
    }
}
