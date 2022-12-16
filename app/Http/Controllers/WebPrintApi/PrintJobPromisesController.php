<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrinterResource;
use App\Http\Resources\PrintJobPromiseResource;
use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\PrintJobPromise;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PrintJobPromisesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PrintJobPromise::class, 'promise');
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

        $promises = $client_application->JobPromises()
            ->latest('name')
            ->with('Printer')
            ->paginate(25);

        return PrintJobPromiseResource::collection(
            $promises
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return PrintJobPromiseResource|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var ClientApplication $client_application */
        $client_application = $request->user();

        $client_printers_uuids = $client_application->Printers()->pluck('uuid');
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'printer' => ['nullable', Rule::in($client_printers_uuids)],
            'available_printers' => ['nullable', 'array'],
            'available_printers.*' => ['string', Rule::in($client_printers_uuids)],
            'type' => 'required',
            'ppd_options' => ['nullable', 'array'],
            'ppd_options.*' => ['required', 'string'],
            'meta' => ['nullable', 'array'],
            'meta.*' => ['required', 'string'],
            'content' => 'nullable',
            'file_name' => 'required_with:content',
            'headless' => ['nullable', 'boolean'],
        ]);

        $promise = new PrintJobPromise;
        $promise->client_application_id = $client_application->id;
        $promise->status = $validated['headless'] ?? false ? 'ready' : 'new';
        $promise->name = $validated['name'];
        $promise->type = $validated['type'];
        $promise->ppd_options = $validated['ppd_options'] ?? null;
        $promise->meta = $validated['meta'] ?? null;
        $promise->file_name = $validated['file_name'] ?? null;

        $promise->save();

        if ($validated['content'] ?? null){
            if(strlen($validated['content']) < 1024 && !preg_match('/[^\x20-\x7e\n]/', $validated['content'])){
                $promise->content = $validated['content'];
            } else {
                Storage::put($promise->content_file = 'jobs/'.Str::random(40).'.dat', $validated['content']);
            }
            $promise->size = strlen($validated['content']);
            $promise->save();
        }

        if($validated['available_printers'] ?? null){
            $uuids = $validated['available_printers'];
        } else {
            $uuids = $client_application->Printers()->forType($promise->type)->pluck('uuid');
        }

        $selected_printer = $client_application->Printers()->where('uuid', $validated['printer'] ?? null)->first();
        if($selected_printer) {
            $promise->printer_id = $selected_printer->id;
            $uuids[] = $selected_printer->uuid;
        }
        $promise->save();

        $available_printers = $client_application->Printers()->whereIn('uuid', $uuids)->get();

        $promise->AvailablePrinters()->sync($available_printers);

        if(!$promise->printer_id && $available_printers->count() == 1) {
            $promise->printer_id = $available_printers->first()->id;
            $promise->save();
        }

        if($promise->isReadyToPrint())
            $promise->sendForPrinting();

        $promise->load(['AvailablePrinters','Printer','PrintJob']);
        return new PrintJobPromiseResource($promise);
    }

    /**
     * Display the specified resource.
     *
     * @param PrintJobPromise $promise
     *
     * @return PrintJobPromiseResource|\Illuminate\Http\Response
     */
    public function show(PrintJobPromise $promise)
    {
        $promise->load(['AvailablePrinters','Printer','PrintJob']);
        return new PrintJobPromiseResource($promise);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request         $request
     * @param PrintJobPromise $promise
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrintJobPromise $promise)
    {
        $available_printers = $promise->AvailablePrinters()->pluck('uuid');
        $validated = $request->validate([
            'status' => ['nullable', 'in:ready'],
            'name' => ['nullable', 'string'],
            'printer' => ['nullable', Rule::in($available_printers)],
            'ppd_options' => ['nullable', 'array'],
            'ppd_options.*' => ['required', 'string'],
            'meta' => ['nullable', 'array'],
            'meta.*' => ['required', 'string'],
        ]);

        $promise->status = $validated['status'] ?? $promise->status;
        $promise->name = $validated['name'] ?? $promise->name;
        $promise->ppd_options = $validated['ppd_options'] ?? $promise->ppd_options;
        $promise->meta = $validated['meta'] ?? $promise->meta;
        $promise->printer_id = optional($promise->AvailablePrinters()->where('uuid', $validated['printer'] ?? null)->first())->id ?? $promise->printer_id;

        $promise->save();

        if($promise->isReadyToPrint())
            $promise->sendForPrinting();

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PrintJobPromise $promise
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrintJobPromise $promise)
    {
        $promise->status = 'canceled';
        $promise->save();

        return response()->noContent();
    }
}
