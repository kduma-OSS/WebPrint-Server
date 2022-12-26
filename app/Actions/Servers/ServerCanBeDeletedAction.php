<?php

namespace App\Actions\Servers;

use App\Models\PrintServer;

class ServerCanBeDeletedAction
{
    public function handle(PrintServer $server): bool
    {
        return $server->Printers()->count() === 0;
    }
}
