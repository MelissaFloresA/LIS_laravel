<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnviarCredencialesEmpresa extends Notification
{
    public $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Empresa registrada')
            ->greeting('Hola ' . $notifiable->Nombre_Contacto)
            ->line('Su cuenta ha sido creada exitosamente.')
            ->line('Contraseña generada: ' . $this->password)
           
            ->salutation('Saludos, Equipo de La Cuponera');
    }
}