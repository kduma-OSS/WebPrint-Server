<?php

namespace App\Http\Livewire\Printers;

use App\Actions\Printers\UpdatePrinterAction;
use App\Models\Printer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class UpdatePrinter extends Component
{
    use AuthorizesRequests;

    /**
     * @var Printer
     */
    public Printer $printer;

    /**
     * @var string
     */
    public $name;

    /**
     * @var ?string
     */
    public $location;

    protected $rules = [
        'name' => ['required', 'string', 'min:1', 'max:255'],
        'location' => ['nullable', 'string', 'min:1', 'max:255'],
    ];

    public function mount(Printer $printer)
    {
        $this->printer = $printer;
        $this->name = $printer->name;
        $this->location = $printer->location;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save(UpdatePrinterAction $action)
    {
        $this->authorize('update', $this->printer);
        $this->validate();

        $action->handle($this->printer, $this->name, $this->location);

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
    }

    public function render()
    {
        return view('livewire.printers.update-printer');
    }
}
