<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Services\PosPointsService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserPointsResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var mixed
     */
    public $resource;


    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'points_earned' => $this->earnedPoints(),
            'points_available'  => $this->availablePoints(),
            'points_spent' => $this->spentPoints(),
            'inactive_points' => $this->inactivePoints(),
            'history' => UserPointsHistoryResource::collection($this->historyPoints()),
        ];
    }
}
