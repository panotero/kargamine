<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GenericMail extends Mailable
{

    public string $subjectText;
    public array $body;

    public function __construct(string $subject, string $message, array $body)
    {
        $this->subjectText = $subject;
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
            ->view('emails.generic')
            ->with([
                'body' => $this->body,
            ]);
    }
}
