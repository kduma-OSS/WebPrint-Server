<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin \App\Models\PrintJobPromise */
class PrintJobPromiseResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid'                     => $this->uuid,
            'status'                   => $this->status,
            'name'                     => $this->name,
            'type'                     => $this->type,
            'ppd_options'              => $this->ppd_options,
            'content_available'        => $this->content || $this->content_file,
            'file_name'                => $this->file_name,

            'size'                     => $this->size,

            'meta'                     => $this->meta,

            'available_printers' => PrinterResource::collection($this->whenLoaded('AvailablePrinters')),
            'selected_printer'       => new PrinterResource($this->whenLoaded('Printer')),

            $this->mergeWhen(Auth::user()->can('viewField', [$this->resource, 'timestamps']), [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]),

//            'print_job_id'          => $this->print_job_id,
        ];
    }
}
