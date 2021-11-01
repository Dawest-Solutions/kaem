<?php

namespace App\Http\Resources;

use App\Models\Prize;
use App\Services\PosPointsService;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class PrizeDetailsResource extends JsonResource
{
    public PosPointsService $posPointsService;

    public function __construct(Prize $resource, PosPointsService $posPointsService)
    {
        parent::__construct($resource);
        $this->posPointsService = $posPointsService;
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge((new PrizeResource($this->resource))->toArray($request), [
                'lackingPointsToPurchased' => $this->posPointsService->lackingPointsToPurchased($this->resource),
                'availablePoints' => $this->posPointsService->availablePoints(),
            ]);
    }
}
