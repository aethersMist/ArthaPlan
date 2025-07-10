<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Akun Anda - ArthaPlan')
            ->greeting('Halo!')
            ->line('Silakan klik tombol di bawah untuk memverifikasi email Anda.')
            ->action('Verifikasi Email', $verificationUrl)
            ->line('Jika Anda tidak membuat akun, abaikan email ini.')
            ->salutation('Salam hangat, ArthaPlan');
    }
}
