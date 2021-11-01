<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'number_pos_main' => $this->number_pos_main,
            'number_pos' => $this->number_pos,
            'company_name' => $this->company_name,
            'isNetwork' => $this->isNetwork,
            'posAdditional' => PosResource::collection($this->whenLoaded('posAdditional'))
        ];
    }
}
