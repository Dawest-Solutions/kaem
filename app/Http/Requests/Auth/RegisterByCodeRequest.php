<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class RegisterByCodeRequest extends BaseRequest
{
    protected array $baseRules = [
        'code' => 'required|regex:/(^[A-Za-z0-9]+$)+/|exists:users,register_code'
    ];

    public function searchRegisterByCode(): array
    {
        return $this->baseRules;
    }

    public function registerUserByCode(): array
    {
        return array_merge($this->baseRules, [
            'password' => ['required', 'confirmed', 'min:8', new StrongPassword],
            'pesel' => 'required_if:tax_declaration,pit',
            'birth_date' => 'required_if:tax_declaration,pit|date:d-m-Y|nullable',
            'street' => 'required_if:tax_declaration,pit',
            'building_number' => 'required_if:tax_declaration,pit',
            'apartment_number' => '',
            'postal_code' => 'required_if:tax_declaration,pit','regex:/^[0-9]{2}(\-[0-9]{3})?$/|nullable',
            'city' => 'required_if:tax_declaration,pit',
            'borough' => 'required_if:tax_declaration,pit',
            'district' => 'required_if:tax_declaration,pit',
            'voivodeship' => 'required_if:tax_declaration,pit',
            'tax_office' => 'required_if:tax_declaration,pit',
            'agreement_1' => 'required',
            'agreement_2' => 'required',
            'agreement_1_text' => 'required',
            'agreement_2_text' => 'required',
            'tax_declaration' => 'required|in:pit,company,company_representative',
        ]);
    }
}
