<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateFortifySettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('fortify', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('registration_enabled', false);
            $blueprint->add('password_resets_enabled', false);
            $blueprint->add('update_passwords_enabled', true);
            $blueprint->add('update_profile_enabled', true);
            $blueprint->add('two_factor_authentication_enabled', true);
        });
    }
}
