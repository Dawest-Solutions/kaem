<?php

namespace App\Http\Resources;

use App\Models\Pos;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoalsResultsCollection extends ResourceCollection
{
    public function __construct($resource, Pos $pos)
    {
        parent::__construct($resource);
        $this->pos = $pos;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'pos' => new PosResource($this->pos),
            'period' => PeriodResource::collection($this->collection),
        ];
    }

}
