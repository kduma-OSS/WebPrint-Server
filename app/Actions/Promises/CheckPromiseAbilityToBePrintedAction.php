<?php

namespace App\Actions\Promises;

use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\PrintJobPromise;

class CheckPromiseAbilityToBePrintedAction
{
    public function handle(PrintJobPromise $promise, bool $ready = false): bool
    {
        if (! in_array($promise->status, $ready ? [
            PrintJobPromiseStatusEnum::Ready,
        ] : [
            PrintJobPromiseStatusEnum::Ready, PrintJobPromiseStatusEnum::New,
        ])) {
            return false;
        }

        if (! $promise->content && ! $promise->content_file) {
            return false;
        }

        return (bool) $promise->printer_id;
    }
}
