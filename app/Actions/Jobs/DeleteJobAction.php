<?php

namespace App\Actions\Jobs;

use App\Actions\Promises\DeletePromiseAction;
use App\Models\Printer;
use App\Models\PrintJob;

class DeleteJobAction
{
    public function __construct(
        protected DeletePromiseAction $deletePromiseAction,
        protected ClearJobContentAction $clearJobContentAction,
    ) {
    }

    public function handle(PrintJob $job): void
    {
        if($job->JobPromise)
            $this->deletePromiseAction->handle($job->JobPromise);

        $this->clearJobContentAction->handle($job);

        $job->delete();
    }
}
