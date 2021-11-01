<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageConfirmingSendingToUser extends Mailable
{
    use Queueable, SerializesModels;

    public string $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Zgłoszenie - {$this->title}")
            ->view('mails.message_confirming_sending');
    }
}
