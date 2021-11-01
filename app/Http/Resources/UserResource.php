<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'pesel' => $this->pesel,
            'birth_date' => $this->birth_date,
            'street' => $this->street,
            'building_number' => $this->building_number,
            'apartment_number' => $this->apartment_number,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'borough' => $this->borough,
            'district' => $this->district,
            'voivodeship' => $this->voivodeship,
            'tax_office' => $this->tax_office,
            'email' => $this->email,
            'phone' => $this->phone,
            'tax_declaration' => $this->tax_declaration,
            'roles' => $this->roles,
            'company_name' => $this->whenLoaded('pos', fn() => $this->pos->company_name),
            'customer_number' => $this->whenLoaded('pos', fn() => $this->pos->number_pos),
        ];
    }
}
