<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $body;

    public function __construct($body)
    {
        $this->body = $body;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Electronic Mart - Test Email')
            ->view('emails.test')
            ->with(['body' => $this->body]);
    }
}
