<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrintDialogResource;
use App\Models\PrintDialog;
use App\Models\PrintJobPromise;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrintDialogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  PrintJobPromise  $promise
     * @return PrintDialogResource|Response
     *
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
     * @param  Request  $request
     * @param  PrintJobPromise  $promise
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
     * @param  PrintDialog  $printDialog
     * @return Response
     */
    public function show(PrintDialog $printDialog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  PrintDialog  $printDialog
     * @return Response
     */
    public function update(Request $request, PrintDialog $printDialog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PrintDialog  $printDialog
     * @return Response
     */
    public function destroy(PrintDialog $printDialog)
    {
        //
    }
}
