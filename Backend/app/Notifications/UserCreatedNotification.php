<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification
{
    use Queueable;

    protected $password;

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
            ->subject('Votre compte a été créé')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un compte a été créé pour vous sur notre plateforme.')
            ->line('Voici vos identifiants de connexion :')
            ->line('**Email :** ' . $notifiable->email)
            ->line('**Mot de passe :** ' . $this->password)
            ->line('**Rôle :** ' . $notifiable->role->nom)
            ->line('Vous pouvez maintenant vous connecter avec ces identifiants.')
            ->action('Se connecter', url('/login'))
            ->line('Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe lors de votre première connexion.')
            ->line('Si vous avez des questions, n\'hésitez pas à nous contacter.');
    }
}