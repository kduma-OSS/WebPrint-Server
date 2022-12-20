<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrinterResource;
use App\Models\ClientApplication;
use App\Models\Printer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PrintersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Printer::class, 'printer');

        $this->middleware(function (Request $request, $next) {
            /** @var ClientApplication $client_application */
            $client_application = $request->user();

            abort_if($client_application instanceof ClientApplication === false, 403);

            $client_application->last_active_at = now();
            $client_application->save();

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
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
            ->where('enabled', true)
            ->when($request->get('type'), function (Builder $query, $type): void {
                $query->forType($type);
            })
            ->get()
            ->map(fn (Printer $printer): \App\Http\Resources\PrinterResource => new PrinterResource($printer, $request->get('ppd_options', false)));

        return PrinterResource::collection(
            $printers
        );
    }

    /**
     * Display the specified resource.
     *
     * @return PrinterResource
     */
    public function show(Printer $printer): \App\Http\Resources\PrinterResource
    {
        $printer->load('Server');

        return new PrinterResource($printer);
    }
}
