<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Redefina sua senha — Posts & Comments')
            ->greeting('Olá!')
            ->line('Recebemos uma solicitação para redefinir a senha da sua conta.')
            ->action('Criar nova senha', $this->resetUrl($notifiable))
            ->line('Este link expira em '.config('auth.passwords.users.expire').' minutos e pode ser usado uma única vez.')
            ->line('Se você não fez essa solicitação, ignore esta mensagem. Sua senha continuará a mesma.')
            ->salutation('Posts & Comments');
    }
}
