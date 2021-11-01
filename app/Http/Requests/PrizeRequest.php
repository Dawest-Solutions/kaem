<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrizeRequest extends BaseRequest
{
    public function order()
    {
        return [
            'full_name' => 'required',
            'phone' => 'required',
            'email' => 'email',
            'address' => 'required',
            'postal_code' => 'required|regex:/^[0-9]{2}(\-[0-9]{3})?$/',
            'city' => 'required',
        ];
    }
}
