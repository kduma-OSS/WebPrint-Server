<?php

namespace App\Actions\Apps;

use App\Models\ClientApplication;
use App\Models\PrintServer;

class UpdateAppAction
{
    public function handle(ClientApplication $app, string $name, ?string $url): void
    {
        $app->name = $name;
        $app->url = $url;
        $app->save();
    }
}
