<?php

namespace App\Http\Livewire\Printers;

use App\Actions\Printers\CreatePrinterAction;
use App\Models\Printer;
use App\Models\PrintServer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Spatie\ValidationRules\Rules\Delimited;

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

    /**
     * @var string
     */
    public $uri;

    /**
     * @var string
     */
    public $languages;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'uri' => ['required', 'string', 'min:1', 'max:255'],
            'languages' => [(new Delimited('string|max:25'))->max(10)],
        ];
    }

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

        $languages = collect(explode(',', $this->languages))
            ->map(fn ($language) => trim($language))
            ->map(fn ($language) => strtolower($language))
            ->filter()
            ->toArray();

        $printer = $action->handle($this->server, $this->name, $this->uri, $languages);

        $this->emit('saved');

        return redirect()->route('web-print.printers.show', $printer);
    }

    public function render()
    {
        return view('livewire.printers.create-printer');
    }
}
