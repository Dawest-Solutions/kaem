<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminPOSCreateRequest extends FormRequest
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
            'user_id' => 'required',
            'ph_id' => 'required',
            'number_pos_main' => 'required',
            'number_pos' => 'required',
            'company_name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Uczestnik jest wymagany',
            'ph_id.required' => 'Przedstawiciel handlowy jest wymagany',
            'number_pos_main.required' => 'Numer POS główny jest wymagany',
            'number_pos.required' => 'Numer POS jest wymagany',
            'company_name.required' => 'Nazwa jest wymagana',
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
