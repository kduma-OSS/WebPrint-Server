<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin \App\Models\Printer */
class PrinterResource extends JsonResource
{
    protected ?bool $with_ppd_options = null;

    public function __construct($resource, ?bool $with_ppd_options = null)
    {
        $this->with_ppd_options = $with_ppd_options ?? true;
        parent::__construct($resource);
    }

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
            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'ppd']) && $this->with_ppd_options, [
                'ppd_options' => $this->ppd_options,
                'ppd_options_layout' => $this->ppd_options_layout,
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
