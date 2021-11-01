<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageFromUser extends Mailable
{
    use Queueable, SerializesModels;

    public string $title;
    public string $description;
    public string $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $title, string $description, string $email)
    {
        $this->title = $title;
        $this->description = $description;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(config('mail.from.address'))
            ->subject("Zgłoszenie - {$this->title}")
            ->view('mails.message_from_user');
    }
}
