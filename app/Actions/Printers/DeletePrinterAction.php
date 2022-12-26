<?php

namespace App\Actions\Printers;

use App\Actions\Jobs\DeleteJobAction;
use App\Actions\Promises\DeletePromiseAction;
use App\Actions\Servers\ServerCanBeDeletedAction;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use RuntimeException;

class DeletePrinterAction
{
    public function __construct(
        protected DeleteJobAction $deleteJobAction,
        protected DeletePromiseAction $deletePromiseAction,
    ) {
    }

    public function handle(Printer $printer): void
    {
        $printer->Jobs->each(fn(PrintJob $job) => $this->deleteJobAction->handle($job));

        $printer->JobPromises->each(fn(PrintJobPromise $promise) => $this->deletePromiseAction->handle($promise));

        $printer->ClientApplications()->detach();

        $printer->AvailableToJobPromises()->detach();

        $printer->delete();
    }
}
