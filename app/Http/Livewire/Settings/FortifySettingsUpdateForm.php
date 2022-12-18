<?php

namespace App\Http\Livewire\Settings;

use App\Settings\FortifySettings;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FortifySettingsUpdateForm extends Component
{
    use AuthorizesRequests;

    /**
     * @var bool
     */
    public $registration_enabled;

    /**
     * @var bool
     */
    public $password_resets_enabled;

    /**
     * @var bool
     */
    public $update_passwords_enabled;

    /**
     * @var bool
     */
    public $update_profile_enabled;

    /**
     * @var bool
     */
    public $two_factor_authentication_enabled;

    protected $rules = [
        'registration_enabled' => 'boolean',
        'password_resets_enabled' => 'boolean',
        'update_passwords_enabled' => 'boolean',
        'update_profile_enabled' => 'boolean',
        'two_factor_authentication_enabled' => 'boolean',
    ];

    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount(FortifySettings $settings)
    {
        $this->authorize('viewAny', FortifySettings::class);
        $user = Auth::user();

        $this->registration_enabled = $user->can('view', [FortifySettings::class, 'registration_enabled']) ? $settings->registration_enabled : false;
        $this->password_resets_enabled = $user->can('view', [FortifySettings::class, 'password_resets_enabled']) ? $settings->password_resets_enabled : false;
        $this->update_passwords_enabled = $user->can('view', [FortifySettings::class, 'update_passwords_enabled']) ? $settings->update_passwords_enabled : false;
        $this->update_profile_enabled = $user->can('view', [FortifySettings::class, 'update_profile_enabled']) ? $settings->update_profile_enabled : false;
        $this->two_factor_authentication_enabled = $user->can('view', [FortifySettings::class, 'two_factor_authentication_enabled']) ? $settings->two_factor_authentication_enabled : false;
    }

    public function updateFortifySettings(FortifySettings $settings)
    {
        $validatedData = $this->validate(
            collect($this->rules)
                ->filter(function ($rule, $key) {
                    return Auth::user()->can('update', [FortifySettings::class, $key]);
                })
                ->toArray()
        );

        collect($validatedData)
            ->each(function ($value, $key) use ($settings) {
                $settings->{$key} = $value;
            });

        $settings->save();

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
    }

    public function render()
    {
        return view('livewire.settings.fortify-settings-update-form');
    }
}