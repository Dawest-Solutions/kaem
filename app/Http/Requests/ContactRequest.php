<?php

namespace App\Http\Requests;

class ContactRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function sendMessageFromUser()
    {
        return [
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ];
    }
}
