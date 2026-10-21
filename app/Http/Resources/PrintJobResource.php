<?php

namespace App\Http\Resources;

use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin PrintJob */
class PrintJobResource extends JsonResource
{
    /**
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        return [
            'ulid' => $this->ulid,
            'status' => $this->status,
            'status_message' => $this->status_message,
            'name' => $this->name,
            'ppd' => $this->ppd,
            'file_name' => $this->file_name,
            'size' => $this->size,
            'printer' => new PrinterResource($this->whenLoaded('Printer')),
            'promise' => new PrintJobPromiseResource($this->whenLoaded('JobPromise')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'timestamps']), [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]),
        ];
    }
}
