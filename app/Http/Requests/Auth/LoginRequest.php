<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Rules\AcceptedIf;
use App\Rules\StrongPassword;
use App\Rules\ValidateRegisterFormPassword;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function login(): array
    {
        return [
            'email' => 'required|email',
            'password' => ['required', 'min:8', new StrongPassword],
        ];
    }
}
