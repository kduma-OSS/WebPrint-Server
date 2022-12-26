<?php

namespace App\Actions\Servers;

use App\Models\PrintServer;

class UpdateServerAction
{
    public function handle(PrintServer $server, string $name, ?string $location): void
    {
        $server->name = $name;
        $server->location = $location;
        $server->save();
    }
}
