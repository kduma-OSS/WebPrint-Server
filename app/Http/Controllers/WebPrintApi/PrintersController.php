<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrinterResource;
use App\Models\ClientApplication;
use App\Models\Printer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PrintersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Printer::class, 'printer');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        /** @var ClientApplication $client_application */
        $client_application = $request->user();

        $printers = $client_application->Printers()
            ->orderBy('name')
            ->with('Server')
            ->when($request->get('type'), function (Builder $query, $type) {
                $query->forType($type);
            })
            ->get()
            ->map(fn (Printer $printer) => new PrinterResource($printer, $request->get('ppd_options', false)));

        return PrinterResource::collection(
            $printers
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Printer $printer
     *
     * @return PrinterResource
     */
    public function show(Printer $printer)
    {
        return new PrinterResource($printer);
    }
}
