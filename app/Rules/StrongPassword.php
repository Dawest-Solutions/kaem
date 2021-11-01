<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    private array $errors;

    public function __construct()
    {
        $this->errors = array();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!preg_match("/\d/", $value)) {
            $this->errors[] = trans('validation.digit_required');
        }
        if (!preg_match("/[A-Z]/", $value)) {
            $this->errors[] = trans('validation.capital_required');
        }
        if (!preg_match("/[a-z]/", $value)) {
            $this->errors[] = trans('validation.lowercase_letter_required');
        }
        if (!preg_match("/\W/", $value)) {
            $this->errors[] = trans('validation.special_character_required');
        }
        if (preg_match("/\s/", $value)) {
            $this->errors[] = trans('validation.white_char_forbidden');
        }

        return count($this->errors) > 0 ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errors;
    }
}
