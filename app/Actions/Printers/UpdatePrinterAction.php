<?php

namespace App\Actions\Printers;

use App\Models\Printer;
use App\Models\PrintServer;

class UpdatePrinterAction
{
    public function handle(Printer $printer, string $name, ?string $location): void
    {
        $printer->name = $name;
        $printer->location = $location;
        $printer->save();
    }
}
