<?php

namespace App\Actions\Apps;

use App\Actions\Jobs\DeleteJobAction;
use App\Actions\Promises\DeletePromiseAction;
use App\Models\ClientApplication;
use App\Models\PrintJob;
use App\Models\PrintJobPromise;

class DeleteAppAction
{
    public function __construct(
        protected DeleteJobAction $deleteJobAction,
        protected DeletePromiseAction $deletePromiseAction,
    ) {
    }

    public function handle(ClientApplication $app): void
    {
        $app->tokens->each->delete();

        $app->Jobs->each(fn (PrintJob $job) => $this->deleteJobAction->handle($job));

        $app->JobPromises->each(fn (PrintJobPromise $promise) => $this->deletePromiseAction->handle($promise));

        $app->Printers()->detach();

        $app->delete();
    }
}
