<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $mail;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $mail)
    {
        $this->name = $name;
        $this->mail = $mail;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->to($this->mail)
            ->subject('テストメール')
            ->view('emails.test')
            ->with([
                'name' => $this->name,
            ]);
    }
}
