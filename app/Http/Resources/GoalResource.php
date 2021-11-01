<?php

namespace App\Http\Resources;

use App\Models\Period;
use App\Services\GoalsService;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $goalsService = new GoalsService($this->resource);
        $period = Period::with(['results' => function ($query) {
            $query->where('number_pos', $this->pos->number_pos);
            $query->with('pos');
        }]);

        return [
            'pos' => new PosResource($goalsService->getPos()),
            'period' => PeriodResource::collection($goalsService->getByPeriod($period)),
        ];
    }
}
