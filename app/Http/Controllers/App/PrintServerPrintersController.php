<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Printer;
use App\Models\PrintServer;
use Illuminate\Http\Request;

class PrintServerPrintersController extends Controller
{
    public function index(PrintServer $printServer)
    {
        //
    }

    public function create(PrintServer $printServer)
    {
        //
    }

    public function store(Request $request, PrintServer $printServer)
    {
        //
    }

    public function show(PrintServer $printServer, Printer $printer)
    {
        //
    }

    public function edit(PrintServer $printServer, Printer $printer)
    {
        //
    }

    public function update(Request $request, PrintServer $printServer, Printer $printer)
    {
        //
    }

    public function destroy(PrintServer $printServer, Printer $printer)
    {
        //
    }
}
