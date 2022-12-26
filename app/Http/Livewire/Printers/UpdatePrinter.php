<?php

namespace App\Http\Livewire\Printers;

use App\Actions\Printers\UpdatePrinterAction;
use App\Models\Printer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Spatie\ValidationRules\Rules\Delimited;

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
     * @var string
     */
    public $uri;

    /**
     * @var string
     */
    public $languages;

    /**
     * @var ?string
     */
    public $location;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'uri' => ['required', 'url', 'min:1', 'max:255'],
            'languages' => [(new Delimited('string|max:25'))->max(10)],
            'location' => ['nullable', 'string', 'min:1', 'max:255'],
        ];
    }

    public function mount(Printer $printer)
    {
        $this->printer = $printer;
        $this->name = $printer->name;
        $this->location = $printer->location;
        $this->uri = $printer->uri;
        $this->languages = implode(', ', $printer->raw_languages_supported);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save(UpdatePrinterAction $action)
    {
        $this->authorize('update', $this->printer);
        $this->validate();

        $languages = collect(explode(',', $this->languages))
            ->map(fn ($language) => trim($language))
            ->map(fn ($language) => strtolower($language))
            ->filter()
            ->toArray();

        $action->handle($this->printer, $this->name, $this->uri, $languages, $this->location);

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
    }

    public function render()
    {
        return view('livewire.printers.update-printer');
    }
}
