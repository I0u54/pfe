<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Like extends Notification
{
    use Queueable;

    protected $user_like;
    protected $idTweet;

    /**
     * Create a new notification instance.
     */
    public function __construct($user_like , $id)
    {
        $this->user_like = $user_like;
        $this->idTweet = $id;
        
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
            'idUser' => $this->user_like->id ,
            'idTweet' => $this->idTweet ,
            'name' => $this->user_like->name ,
            'pseudo' => $this->user_like->pseudo ,
            'pp' => $this->user_like->extra_user->pp ?? null
        ];
    }
}
