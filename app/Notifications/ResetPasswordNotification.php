<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Permintaan Reset Kata Sandi - ArthaPlan')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi Anda.')
            ->action('Reset Kata Sandi', $url)
            ->line('Tautan ini akan kedaluwarsa dalam 60 menit.')
            ->line('Jika Anda tidak meminta reset kata sandi, abaikan email ini.')
            ->salutation('Salam hangat, ArthaPlan');
    }
}
