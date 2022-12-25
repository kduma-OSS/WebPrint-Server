<?php

namespace App\Actions\Promises;

use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\PrintJobPromise;

class CancelPromiseAction
{
    public function handle(PrintJobPromise $promise): void
    {
        if (! in_array(
            needle: $promise->status,
            haystack: [
                PrintJobPromiseStatusEnum::Draft,
                PrintJobPromiseStatusEnum::New,
                PrintJobPromiseStatusEnum::Ready,
            ],
        )) {
            throw new \RuntimeException('Cannot cancel a promise that is not in draft, new or ready state.');
        }

        $promise->status = PrintJobPromiseStatusEnum::Cancelled;
        $promise->save();
    }
}
