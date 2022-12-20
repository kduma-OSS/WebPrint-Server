<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Printer;
use App\Models\PrintServer;
use Illuminate\Http\Request;

class PrintServerPrintersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Printer::class, 'printer');
    }

    public function index(PrintServer $server)
    {
        $this->authorize('viewAny', [Printer::class, $server]);

        $printers = $server->printers()
            ->orderBy('name')
            ->withCount('jobs')
            ->get();

        return view('app.print-servers.printers.index', [
            'server' => $server,
            'printers' => $printers,
        ]);
    }

    public function create(PrintServer $server): void
    {
        $this->authorize('create', [Printer::class, $server]);
    }

    public function store(Request $request, PrintServer $server): void
    {
        //
    }

    public function show(Printer $printer): void
    {
        //
    }

    public function edit(Printer $printer): void
    {
        //
    }

    public function update(Request $request, Printer $printer): void
    {
        //
    }

    public function destroy(Printer $printer): void
    {
        //
    }
}
