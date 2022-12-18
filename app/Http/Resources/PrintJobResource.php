<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin \App\Models\PrintJob */
class PrintJobResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'ulid'           => $this->ulid,
            'status'         => $this->status,
            'status_message' => $this->status_message,
            'name'           => $this->name,
            'ppd'            => $this->ppd,
            'file_name'      => $this->file_name,
            'size'           => $this->size,
            'printer'        => new PrinterResource($this->whenLoaded('Printer')),
            'promise'        => new PrintJobPromiseResource($this->whenLoaded('JobPromise')),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,

            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'timestamps']), [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]),
        ];
    }
}
