<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterByCodeResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'register_code' => $this->register_code,
            'previous_system_user' => $this->previous_system_user,
            'distributor_name' => $this->whenLoaded('pos', fn() => $this->pos->first()->company_name),
        ];
    }
}
