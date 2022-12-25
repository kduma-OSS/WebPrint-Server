<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Actions\Promises\SetPromiseContentAction;
use App\Http\Controllers\Controller;
use App\Models\ClientApplication;
use App\Models\PrintJobPromise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\HeaderUtils;

class PrintJobPromisesContentController extends Controller
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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(PrintJobPromise $promise)
    {
        $this->authorize('view', $promise);

        if (! $promise->content && ! $promise->content_file) {
            return response(status: 404);
        }

        if (is_null($promise->content)) {
            return \Storage::download($promise->content_file, $promise->file_name, [
                'Content-Type' => 'application/octet-stream',
            ]);
        }

        return response($promise->content, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => HeaderUtils::makeDisposition('attachment', $promise->file_name, Str::slug($promise->file_name, '.')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, PrintJobPromise $promise, SetPromiseContentAction $setPromiseContentAction)
    {
        $this->authorize('update', $promise);

        if ($request->hasFile('content')) {
            $file = $request->file('content');

            $setPromiseContentAction->handle($promise, $file);
        } elseif ($request->has('content')) {
            $validated = $request->validate([
                'content' => 'required',
                'name' => $promise->file_name ? 'nullable' : 'required',
            ]);

            $setPromiseContentAction->handle(
                promise: $promise,
                content: $validated['content'],
                name: $validated['name'] ?? $promise->file_name
            );
        } else {
            $setPromiseContentAction->handle(
                promise: $promise,
                content: $request->getContent(true),
                name: $request->hasHeader('X-File-Name') && $request->header('X-File-Name') ? $request->header('X-File-Name') : null,
            );
        }
        $promise->save();

        if ($promise->isReadyToPrint()) {
            $promise->sendForPrinting();
        }

        return response()->noContent();
    }
}
