<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Rules\StrongPassword;

class ForgotPasswordRequest extends BaseRequest
{
    public function all($keys = null){
        $data = parent::all($keys);
        $data['token'] = $this->route('token');
        return $data;
    }

    /**
     * @return string[]
     */
    public function forgot(): array
    {
        return [
            'email' => 'required|email'
        ];
    }

    public function reset(): array
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:8', new StrongPassword],
        ];
    }
}
