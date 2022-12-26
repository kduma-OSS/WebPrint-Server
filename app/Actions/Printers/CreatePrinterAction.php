<?php

namespace App\Actions\Printers;

use App\Models\Printer;
use App\Models\PrintServer;
use App\Models\Team;

class CreatePrinterAction
{
    public function handle(PrintServer $server, string $name, string $uri = '', array $languages = []): Printer
    {
        $printer = new Printer();
        $printer->name = $name;
        $printer->print_server_id = $server->id;
        $printer->raw_languages_supported = $languages;
        $printer->uri = $uri;
        $printer->save();

        return $printer;
    }
}
