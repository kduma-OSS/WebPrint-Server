<?php

namespace App\Http\Livewire\Printers;

use App\Actions\Printers\UpdatePrinterAction;
use App\Models\Printer;
use App\Rules\ValidPpdJson;
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

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var bool
     */
    public $ppd;

    /**
     * @var ?string
     */
    public $options;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'enabled' => ['bool'],
            'ppd' => ['bool'],
            'options' => ['string', 'nullable', 'json', new ValidPpdJson()],
            'uri' => ['required', 'string', 'min:1', 'max:255', 'regex:/^[a-z]+:\/\/.+/'],
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
        $this->enabled = $printer->enabled;
        $this->ppd = $printer->ppd_support;
        $this->options = $printer->ppd_options ? json_encode($printer->ppd_options, JSON_PRETTY_PRINT) : '';
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

        $action->handle(
            printer: $this->printer,
            name: $this->name,
            uri: $this->uri,
            languages: $languages,
            location: $this->location,
            enabled: $this->enabled,
            ppd_support: $this->ppd,
            ppd_options: $this->options ? json_decode($this->options, true) : null,
        );

        $this->options = $this->printer->ppd_options
            ? json_encode($this->printer->ppd_options, JSON_PRETTY_PRINT)
            : '';

        $this->emit('saved');
    }

    public function render()
    {
        return view('livewire.printers.update-printer');
    }
}
