<?php

namespace App\Actions\Printers;

use App\Models\Printer;

class UpdatePrinterAction
{
    public function handle(
        Printer $printer,
        string $name,
        string $uri,
        array $languages,
        ?string $location,
        bool $enabled,
        bool $ppd_support,
        ?array $ppd_options,
    ): void {
        $printer->name = $name;
        $printer->location = $location;
        $printer->raw_languages_supported = $languages;
        $printer->uri = $uri;
        $printer->ppd_support = $ppd_support;
        $printer->ppd_options = $ppd_options;
        $printer->enabled = $enabled;
        $printer->save();
    }
}
