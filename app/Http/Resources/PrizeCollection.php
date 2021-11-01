<?php

namespace App\Http\Resources;

use App\Models\Prize;
use App\Services\PosPointsService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PrizeCollection extends ResourceCollection
{
    protected $prizeCategories;
    protected $posPointsService;

    public function __construct($resource, $prizeCategories, PosPointsService $posPointsService)
    {
        parent::__construct($resource);
        $this->prizeCategories = $prizeCategories;
        $this->posPointsService = $posPointsService;
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
            'availablePoints' => $this->posPointsService->availablePoints(),
            'categories' => PrizeCategoryResource::collection($this->prizeCategories),
            'data' => $this->collection,
        ];
    }
}
