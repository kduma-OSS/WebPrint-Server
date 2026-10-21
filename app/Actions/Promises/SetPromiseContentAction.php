<?php

namespace App\Actions\Promises;

use App\Models\PrintJobPromise;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class SetPromiseContentAction
{
    /**
     * @param  string|UploadedFile|resource  $content
     */
    public function handle(PrintJobPromise $promise, mixed $content, ?string $name = null): void
    {
        $attributes = $promise->getAttributes();
        $previousFile = $promise->content_file;

        try {
            if ($content instanceof UploadedFile) {
                $this->handleUploadedFile($promise, $content, $name);
            } elseif (is_resource($content)) {
                $this->handleResource($promise, $content, $name);
            } else {
                $this->handleString($promise, $content, $name);
            }

            if (! $promise->saveOrFail()) {
                throw new RuntimeException('Unable to save promise content.');
            }
        } catch (Throwable $exception) {
            if ($promise->content_file && $promise->content_file !== $previousFile) {
                Storage::delete($promise->content_file);
            }

            $promise->setRawAttributes($attributes, true);

            throw $exception;
        }

        if ($previousFile && $previousFile !== $promise->content_file) {
            Storage::delete($previousFile);
        }
    }

    protected function handleUploadedFile(PrintJobPromise $promise, UploadedFile $content, ?string $name = null): void
    {
        $promise->content = null;
        $promise->content_file = 'jobs/'.$content->hashName();
        if (! $content->storeAs('jobs', $content->hashName())) {
            throw new RuntimeException('Unable to store uploaded promise content.');
        }
        $promise->size = $content->getSize();
        $promise->file_name ??= $content->getClientOriginalName();

        if ($name) {
            $promise->file_name = $name;
        }
    }

    protected function handleString(PrintJobPromise $promise, string $content, ?string $name = null): void
    {
        if (strlen($content) < 1024 && ! preg_match('#[^\x20-\x7e\n]#', $content)) {
            $promise->content = $content;
            $promise->content_file = null;
        } else {
            $promise->content = null;
            $promise->content_file = 'jobs/'.Str::random(40).'.dat';
            if (! Storage::put($promise->content_file, $content)) {
                throw new RuntimeException('Unable to store promise content.');
            }
        }

        if ($name) {
            $promise->file_name = $name;
        }

        $promise->size = strlen($content);
    }

    /**
     * @param  resource  $content
     */
    protected function handleResource(PrintJobPromise $promise, mixed $content, ?string $name = null): void
    {
        $tmp_file = Str::random(40).'.dat';

        $promise->content = null;
        $promise->content_file = 'jobs/'.$tmp_file;
        if (! Storage::writeStream($promise->content_file, $content)) {
            throw new RuntimeException('Unable to store streamed promise content.');
        }
        $promise->size = Storage::size($promise->content_file);
        $promise->file_name ??= $tmp_file;
        if ($name) {
            $promise->file_name = $name;
        }
    }
}
