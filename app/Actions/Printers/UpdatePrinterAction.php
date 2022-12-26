<?php

namespace App\Actions\Printers;

use App\Models\Printer;

class UpdatePrinterAction
{
    public function handle(Printer $printer, string $name, string $uri = '', array $languages = [], ?string $location = null): void
    {
        $printer->name = $name;
        $printer->location = $location;
        $printer->raw_languages_supported = $languages;
        $printer->uri = $uri;
        $printer->save();
    }
}
