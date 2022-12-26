<?php

namespace App\Actions\Promises;

use App\Models\Printer;
use App\Models\PrintJobPromise;

class DeletePromiseAction
{
    public function __construct(
        protected ClearPromiseContentAction $clearPromiseContentAction,
    ) {
    }

    public function handle(PrintJobPromise $printer): void
    {
        if($printer->PrintDialog)
            $printer->PrintDialog->delete();

        $printer->AvailablePrinters()->detach();

        $this->clearPromiseContentAction->handle($printer);

        $printer->delete();
    }
}
