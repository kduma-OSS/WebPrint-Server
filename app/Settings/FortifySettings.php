<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class FortifySettings extends Settings
{
    /** Features::registration() */
    public bool $registration_enabled;

    /** Features::resetPasswords() */
    public bool $password_resets_enabled;

    /** Features::updatePasswords() */
    public bool $update_passwords_enabled;

    /** Features::updateProfileInformation() */
    public bool $update_profile_enabled;

    /** Features::twoFactorAuthentication() */
    public bool $two_factor_authentication_enabled;

    public static function group(): string
    {
        return 'fortify';
    }
}
