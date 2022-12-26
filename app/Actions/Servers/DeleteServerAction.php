<?php

namespace App\Actions\Servers;

use App\Actions\Printers\DeletePrinterAction;
use App\Models\Printer;
use App\Models\PrintServer;
use RuntimeException;

class DeleteServerAction
{
    public function __construct(
        protected ServerCanBeDeletedAction $serverCanBeDeletedAction,
        protected DeletePrinterAction $deletePrinterAction,
    ) {
    }

    public function handle(PrintServer $server): void
    {
        if (! $this->serverCanBeDeletedAction->handle($server)) {
            throw new RuntimeException('Server cannot be deleted');
        }

        $server->tokens->each->delete();

        $server->Printers->each(fn(Printer $printer) => $this->deletePrinterAction->handle($printer));

        $server->delete();
    }
}
