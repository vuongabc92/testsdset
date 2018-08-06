<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification {
    
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token) {
       $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        return (new MailMessage)
            ->view('frontend.email.reset-password')
            ->line(_t('email.resetpass.line1'))
            ->action(_t('email.resetpass.action'), url('password/reset', $this->token))
            ->line(_t('email.resetpass.line2'));
    }

}
