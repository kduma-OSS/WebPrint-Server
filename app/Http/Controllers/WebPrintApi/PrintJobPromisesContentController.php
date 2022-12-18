<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use App\Models\PrintJobPromise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\HeaderUtils;

class PrintJobPromisesContentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  PrintJobPromise  $promise
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
        } else {
            return response($promise->content, 200, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => HeaderUtils::makeDisposition('attachment', $promise->file_name, Str::slug($promise->file_name, '.')),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  PrintJobPromise  $promise
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, PrintJobPromise $promise)
    {
        $this->authorize('update', $promise);

        if ($request->hasFile('content')) {
            $file = $request->file('content');
            $promise->content = null;
            if ($promise->content_file) {
                Storage::delete($promise->content_file);
                $promise->content_file = null;
            }
            $promise->save();
            $promise->content_file = $file->store('jobs');
            $promise->file_name ??= $file->getClientOriginalName();
            $promise->size = $file->getSize();
            $promise->save();
        } elseif ($request->has('content')) {
            $validated = $request->validate([
                'content' => 'required',
                'name' => $promise->file_name ? 'nullable' : 'required',
            ]);

            $promise->content = null;
            if ($promise->content_file) {
                Storage::delete($promise->content_file);
                $promise->content_file = null;
            }
            $promise->save();
            if (strlen($validated['content']) < 1024) {
                $promise->content = $validated['content'];
            } else {
                Storage::put($promise->content_file = 'jobs/'.Str::random(40).'.dat', $validated['content']);
            }
            $promise->file_name = $validated['name'] ?? $promise->file_name;
            $promise->size = strlen($validated['content']);
            $promise->save();
        } else {
            $promise->content = null;
            if ($promise->content_file) {
                Storage::delete($promise->content_file);
                $promise->content_file = null;
            }
            $promise->save();
            $name = Str::random(40).'.dat';
            Storage::writeStream($promise->content_file = 'jobs/'.$name, $request->getContent(true));

            $promise->file_name ??= $name;
            if ($request->hasHeader('X-File-Name') && $request->header('X-File-Name')) {
                $promise->file_name = $request->header('X-File-Name');
            }
            $promise->size = Storage::size($promise->content_file);
            $promise->save();
        }

        if ($promise->isReadyToPrint()) {
            $promise->sendForPrinting();
        }

        return response()->noContent();
    }
}
