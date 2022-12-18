<?php

namespace App\Providers;

use App\Models\User;
use App\Settings\FortifySettings;
use App\Settings\GeneralSettings;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Features;

class AppConfigurationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootGeneralSettings();
        $this->bootFortifySettings();
    }

    protected function bootGeneralSettings(): void
    {
        $settings = $this->app->make(GeneralSettings::class);

        if($settings->site_name) {
            config(['app.name' => $settings->site_name]);
        }
    }

    protected function bootFortifySettings(): void
    {
        $settings = $this->app->make(FortifySettings::class);

        if ($settings->registration_enabled && !in_array(Features::registration(), config('fortify.features', []))) {
            config(['fortify.features' => array_merge(config('fortify.features', []), [Features::registration()])]);
        } else {
            $has_users = User::query()->count() > 0;

            if (!$has_users && !in_array(Features::registration(), config('fortify.features', []))) {
                config(['fortify.features' => array_merge(config('fortify.features', []), [Features::registration()])]);
            }
        }

        if ($settings->password_resets_enabled && !in_array(Features::resetPasswords(), config('fortify.features', []))) {
            config(['fortify.features' => array_merge(config('fortify.features', []), [Features::resetPasswords()])]);
        }

        if ($settings->update_passwords_enabled && !in_array(Features::updatePasswords(), config('fortify.features', []))) {
            config(['fortify.features' => array_merge(config('fortify.features', []), [Features::updatePasswords()])]);
        }

        if ($settings->update_profile_enabled && !in_array(Features::updateProfileInformation(), config('fortify.features', []))) {
            config(['fortify.features' => array_merge(config('fortify.features', []), [Features::updateProfileInformation()])]);
        }

        if ($settings->two_factor_authentication_enabled && !in_array(Features::twoFactorAuthentication(), config('fortify.features', []))) {
            config([
                'fortify.features' => array_merge(config('fortify.features', []), [
                    Features::twoFactorAuthentication([
                        'confirm'         => true,
                        'confirmPassword' => true,
                        // 'window' => 0,
                    ])
                ])
            ]);
        }
    }
}
