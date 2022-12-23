<?php

namespace App\Http\Livewire\Settings;

use App\Settings\GeneralSettings;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GeneralSettingsUpdateForm extends Component
{
    use AuthorizesRequests;

    /**
     * @var string
     */
    public $site_name;

    /**
     * @var bool
     */
    public $active;

    /**
     * @var string
     */
    public $language;

    protected $rules = [
        'site_name' => 'nullable|string',
        'active' => 'boolean',
        'language' => ['required', 'string', 'in:en,pl'],
    ];

    /**
     * Prepare the component.
     */
    public function mount(GeneralSettings $settings): void
    {
        $this->authorize('viewAny', GeneralSettings::class);
        $user = Auth::user();

        $this->site_name = $user->can('view', [GeneralSettings::class, 'site_name']) ? $settings->site_name : '';
        $this->active = $user->can('view', [GeneralSettings::class, 'active']) && $settings->active;
        $this->language = $user->can('view', [GeneralSettings::class, 'language']) ? $settings->language : config('app.locale');
    }

    public function updateGeneralSettings(GeneralSettings $settings): void
    {
        $validatedData = $this->validate(
            collect($this->rules)
                ->filter(function ($rule, $key) {
                    return Auth::user()->can('update', [GeneralSettings::class, $key]);
                })
                ->toArray()
        );

        collect($validatedData)
            ->each(function ($value, $key) use ($settings): void {
                $settings->{$key} = $value;
            });

        $settings->save();

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
    }

    public function render()
    {
        return view('livewire.settings.general-settings-update-form');
    }
}
