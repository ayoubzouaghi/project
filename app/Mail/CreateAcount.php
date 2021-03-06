<?php

namespace App\Mail;

use app\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateAcount extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.mail')
            ->subject('Complete your registration')
            ->with([
                'register_token' => $this->user->register_token,
                'email' => $this->user->email,
                'url' => 'http://localhost:4200/auth/register' . '?token=' . $this->user->register_token,
            ]);
    }
}
