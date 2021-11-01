<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminPrizeCreateRequest extends FormRequest
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
            'model' => 'required',
            'name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'visibility' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'model.required' => 'Nazwa nagrody jest wymagana',
            'name.required' => 'Nazwa nagrody jest wymagana',
            'category_id.required' => 'Kategoria nagrody jest wymagana',
            'description.required' => 'Opis nagrody jest wymagany',
            'visibility.required' => 'Widoczność jest wymagana',
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
