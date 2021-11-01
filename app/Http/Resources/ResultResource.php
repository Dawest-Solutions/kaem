<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
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
            'date' => $this->updated_at,
            'realization' => $this->turnover,
            'active_points' => $this->active_points,
            'inactive_points' => $this->inactive_points,
            'status' => $this->status,
            'basic' => [
                'points' => $this->basic_points,
                'lacking_points' => $this->lacking_points_basic,
                'threshold' => $this->threshold_basic,
            ],
            'silver' => [
                'points' => $this->silver_points,
                'lacking_points' => $this->lacking_points_silver,
                'threshold' => $this->threshold_silver,
            ],
            'gold' => [
                'points' => $this->gold_points,
                'lacking_points' => $this->lacking_points_gold,
                'threshold' => $this->threshold_gold,
            ],
        ];
    }
}
