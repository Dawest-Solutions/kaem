<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'description' => "KWARTAŁ {$this->number}",
            'begin_at' => $this->begin_at,
            'end_at' => $this->end_at,
            'isCurrent' => $this->isCurrent(),
            'results' => new ResultResource($this->whenLoaded('results', $this->results->first())),
        ];
    }
}
