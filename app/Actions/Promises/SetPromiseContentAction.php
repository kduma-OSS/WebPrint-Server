<?php

namespace App\Actions\Promises;

use App\Models\PrintJobPromise;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SetPromiseContentAction
{
    public function __construct(
        protected ClearPromiseContentAction $clearPromiseContentAction,
    ){}

    /**
     * @param  string|null  $name
     * @param  string|UploadedFile|resource  $content
     */
    public function handle(PrintJobPromise $promise, mixed $content, string $name = null): void
    {
        if ($content instanceof UploadedFile) {
            $this->handleUploadedFile($promise, $content, $name);
        } elseif (is_resource($content)) {
            $this->handleResource($promise, $content, $name);
        } else {
            $this->handleString($promise, $content, $name);
        }
    }

    protected function handleUploadedFile(PrintJobPromise $promise, UploadedFile $content, string $name = null): void
    {
        $this->clearPromiseContentAction->handle($promise);

        $promise->content_file = $content->store('jobs');
        $promise->size = $content->getSize();
        $promise->file_name ??= $content->getClientOriginalName();

        if ($name) {
            $promise->file_name = $name;
        }

        $promise->save();
    }

    protected function handleString(PrintJobPromise $promise, string $content, string $name = null): void
    {
        $this->clearPromiseContentAction->handle($promise);

        if (strlen($content) < 1024 && ! preg_match('#[^\x20-\x7e\n]#', $content)) {
            $promise->content = $content;
            $promise->content_file = null;
        } else {
            $promise->content = null;
            $promise->content_file = 'jobs/'.Str::random(40).'.dat';
            Storage::put($promise->content_file, $content);
        }

        if ($name) {
            $promise->file_name = $name;
        }

        $promise->size = strlen($content);
        $promise->save();
    }

    /**
     * @param  resource  $content
     */
    protected function handleResource(PrintJobPromise $promise, mixed $content, string $name = null): void
    {
        $this->clearPromiseContentAction->handle($promise);

        $tmp_file = Str::random(40).'.dat';

        $promise->content_file = 'jobs/'.$tmp_file;
        Storage::writeStream($promise->content_file, $content);
        $promise->size = Storage::size($promise->content_file);
        $promise->file_name ??= $tmp_file;
        if ($name) {
            $promise->file_name = $name;
        }

        $promise->save();
    }
}
