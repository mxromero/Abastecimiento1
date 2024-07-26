<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification
{
    use Queueable;

    protected $user;
    protected $temporaryPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $temporaryPassword)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                ->subject('Bienvenido al Portal de Productores')
                ->greeting('Hola ' . $this->user->name)
                ->line('¡Bienvenido a nuestra comunidad de productores! Nos complace informarle que su perfil ha sido creado exitosamente en nuestro portal de productores.')
                ->line('Con los siguientes datos:')
                ->line('Nombre: ' . $this->user->name)
                ->line('RUT: ' . $this->user->rut)
                ->line('Email: ' . $this->user->email)
                ->line('Contraseña temporal: ' . $this->temporaryPassword)
                ->line('Por favor, cambie su contraseña la primera vez que inicie sesión.')
                ->line('Puede acceder a su cuenta usando el siguiente enlace:')
                ->action('Acceder al portal', url('/login')) // Aquí se añade el botón con la URL
                ->line('Esperamos que disfrute de esta plataforma y estamos a su disposición para cualquier consulta.');
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
