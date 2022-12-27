<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin \App\Models\Printer */
class PrinterResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'ulid' => $this->ulid,
            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'server']), [
                'server' => new PrintServerResource($this->whenLoaded('Server')),
            ]),
            'name' => $this->name,
            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'location']), [
                'location' => $this->location,
            ]),
            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'ppd']), [
                'ppd_support' => $this->ppd_support,
            ]),
            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'raw_languages_supported']), [
                'raw_languages_supported' => $this->raw_languages_supported,
            ]),
            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'uri']), [
                'uri' => $this->uri,
            ]),
            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'timestamps']), [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]),
        ];
    }
}
