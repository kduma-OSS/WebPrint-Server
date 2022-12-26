<?php

namespace App\Actions\Promises;

use App\Models\PrintJobPromise;
use Illuminate\Support\Facades\Storage;

class ClearPromiseContentAction
{
    public function handle(PrintJobPromise $promise)
    {
        if ($promise->content_file && Storage::exists($promise->content_file)) {
            Storage::delete($promise->content_file);
        }

        $promise->content = null;
        $promise->size = null;
        $promise->content_file = null;

        $promise->save();
    }
}
