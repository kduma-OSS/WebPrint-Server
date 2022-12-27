<?php

namespace App\Actions\Apps;

use App\Models\ClientApplication;

class UpdateAppPrintersAction
{
    public function handle(ClientApplication $app, array $ulids): void
    {
        $ids = $app->Team->Printers()->whereIn('printers.ulid', $ulids)->pluck('printers.id');

        $app->Printers()->sync($ids);
    }
}
