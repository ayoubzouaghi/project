<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class forgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $reset;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reset)
    {
        $this->reset = $reset;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.forgetpassword', [
            'token' => $this->reset['token'],
            'email' => $this->reset['email'],
            'url' => url('http://localhost:4200/auth/reset-password') . '?token=' . $this->reset['token'],

        ]);
    }
}
