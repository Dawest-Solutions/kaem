<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminUserCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'pesel' => 'required',
            'email' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Imie jest wymagane',
            'last_name.required' => 'Nazwisko jest wymagane',
            'pesel.required' => 'PESEL jest wymagany',
            'email.required' => 'Email jest wymagany',
            'password.required' => 'Hasło jest wymagane',
        ];
    }

    /**
     * @return array
     */
    public function filter(): array
    {
        return array_filter($this->all());
    }
}
