<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class follow extends Notification
{
    use Queueable;

    private $user_follower ;

    /**
     * Create a new notification instance.
     */
    public function __construct( $user_follower)
    {
        $this->user_follower = $user_follower;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'idUser' => $this->user_follower->id ,
            'name' => $this->user_follower->name ,
            'pseudo' => $this->user_follower->pseudo ,
            'pp' => $this->user_follower->extra_user->pp ?? null
        ];
    }
}
