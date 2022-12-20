<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrintDialogResource;
use App\Models\ClientApplication;
use App\Models\PrintDialog;
use App\Models\PrintJobPromise;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrintDialogsController extends Controller
{
    public function __construct()
    {
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
     * @return PrintDialogResource|Response
     * @throws AuthorizationException
     */
    public function index(PrintJobPromise $promise)
    {
        $this->authorize('view', $promise);

        if ($promise->PrintDialog === null) {
            return response(status: 404);
        }

        return new PrintDialogResource($promise->PrintDialog);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return PrintDialogResource
     *
     * @throws AuthorizationException
     */
    public function store(Request $request, PrintJobPromise $promise)
    {
        $this->authorize('update', $promise);

        $validated = $request->validate([
            'restricted_ip' => ['nullable', 'ip'],
            'redirect_url' => ['nullable', 'url'],
            'auto_print' => ['nullable', 'boolean'],
        ]);

        if ($promise->PrintDialog !== null) {
            $promise->PrintDialog->delete();
        }

        $dialog = new PrintDialog();
        $dialog->auto_print = $validated['auto_print'] ?? true;
        $dialog->redirect_url = $validated['redirect_url'] ?? null;
        $dialog->restricted_ip = $validated['restricted_ip'] ?? null;
        $promise->PrintDialog()->save($dialog);

        return new PrintDialogResource($dialog->fresh());
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(PrintDialog $printDialog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, PrintDialog $printDialog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(PrintDialog $printDialog)
    {
        //
    }
}
