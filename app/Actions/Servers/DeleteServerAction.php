<?php

namespace App\Actions\Servers;

use App\Models\PrintServer;
use RuntimeException;

class DeleteServerAction
{
    public function __construct(
        protected ServerCanBeDeletedAction $serverCanBeDeletedAction
    ) {
    }

    public function handle(PrintServer $server): void
    {
        if (! $this->serverCanBeDeletedAction->handle($server)) {
            throw new RuntimeException('Server cannot be deleted');
        }

        $server->tokens->each->delete();

        $server->delete();
    }
}
