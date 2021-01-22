<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PrintDialog */
class PrintDialogResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid'          => $this->uuid,
            'status'        => $this->status,
            'auto_print'    => $this->auto_print,
            'redirect_url'  => $this->redirect_url,
            'restricted_ip' => $this->restricted_ip,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'link'          => $this->link,

            'promise' => new PrintJobPromiseResource($this->whenLoaded('JobPromise')),
        ];
    }
}
