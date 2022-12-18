<?php

namespace App\Settings;

class GeneralSettings extends \Spatie\LaravelSettings\Settings
{
    public string $site_name;

    public bool $active;

    public static function group(): string
    {
        return 'general';
    }
}
