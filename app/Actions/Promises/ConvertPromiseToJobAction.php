<?php

namespace App\Actions\Promises;

use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\PrintJob;
use App\Models\PrintJobPromise;

class ConvertPromiseToJobAction
{
    public function __construct(
        protected CheckPromiseAbilityToBePrintedAction $checkPromiseAbilityToBePrintedAction,
    ) {
    }

    public function handle(PrintJobPromise $promise): ?PrintJob
    {
        if (! $this->checkPromiseAbilityToBePrintedAction->handle($promise)) {
            return null;
        }

        $job = new PrintJob();
        $job->client_application_id = $promise->client_application_id;
        $job->printer_id = $promise->printer_id;
        $job->name = $promise->name;
        $job->ppd = $promise->type == 'ppd';
        $job->ppd_options = $promise->ppd_options;
        $job->content = $promise->content;
        $job->content_file = $promise->content_file;
        $job->file_name = $promise->file_name;
        $job->size = $promise->size;
        $job->save();

        $promise->print_job_id = $job->id;
        $promise->status = PrintJobPromiseStatusEnum::SentToPrinter;
        $promise->save();

        return $job;
    }
}
