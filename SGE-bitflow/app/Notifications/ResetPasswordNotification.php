<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Restablece tu contraseña')
            ->line('Se ha creado tu usuario. Haz clic en el siguiente botón para establecer tu contraseña:')
            ->action('Establecer contraseña', $url)
            ->line('Si no solicitaste este registro, puedes ignorar este correo.');
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
