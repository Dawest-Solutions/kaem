<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\MessageConfirmingSendingToUser;
use App\Mail\MessageFromUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendMessageFromUser(ContactRequest $request)
    {
        try{
            Mail::send(new MessageFromUser($request->subject, $request->message, $request->email));
            Mail::to($request->email)
                ->send(new MessageConfirmingSendingToUser($request->subject));
                
            return $this->success('','Great. Message sent.');
        } catch (Exception $ex) {
            return $this->error('Błąd.');
        }
    }
}
