<?php

namespace Tests\Feature;

use App\Settings\FortifySettings;
use Laravel\Fortify\Features;

trait EnableFortifyFeatures
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! in_array(Features::registration(), config('fortify.features', []))) {
            config(['fortify.features' => array_merge(config('fortify.features', []), [Features::registration()])]);
        }

        if (! in_array(Features::resetPasswords(), config('fortify.features', []))) {
            config(['fortify.features' => array_merge(config('fortify.features', []), [Features::resetPasswords()])]);
        }

        if (! in_array(Features::updatePasswords(), config('fortify.features', []))) {
            config(['fortify.features' => array_merge(config('fortify.features', []), [Features::updatePasswords()])]);
        }

        if (! in_array(Features::updateProfileInformation(), config('fortify.features', []))) {
            config(['fortify.features' => array_merge(config('fortify.features', []), [Features::updateProfileInformation()])]);
        }

        if (! in_array(Features::twoFactorAuthentication(), config('fortify.features', []))) {
            config([
                'fortify.features' => array_merge(config('fortify.features', []), [
                    Features::twoFactorAuthentication([
                        'confirm' => true,
                        'confirmPassword' => true,
                        // 'window' => 0,
                    ]),
                ]),
            ]);
        }

        app(FortifySettings::class)->fill([
            'registration_enabled' => true,
            'password_resets_enabled' => true,
            'update_passwords_enabled' => true,
            'update_profile_enabled' => true,
            'two_factor_authentication_enabled' => true,
        ])->save();
    }
}
