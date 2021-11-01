<?php

namespace App\Http\Resources;

use App\Services\PosPointsService;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class PrizeOrderResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->whenLoaded('prize', fn() => $this->prize->name),
            'points' => $this->value,
            'saldo' => $this->saldo,
            'date' => $this->order_date,
            'status' => $this->status->name,
        ];
    }
}
