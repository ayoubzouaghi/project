<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Lang;
use app\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateAcount extends Notification
{
    use Queueable;
    protected $user;
    public $register_token_url;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {

        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $register_token_url = $this->user->register_token;
        $url = url(env('front_url')) . '?token=' . $register_token_url;

        return (new MailMessage)
            ->subject(Lang::get('Create account Notification'))
            ->line(Lang::get('You are receiving this email for creating your account.'))
            ->action(Lang::get('Complete your registration '), $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
