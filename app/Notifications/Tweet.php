<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Tweet extends Notification
{
    use Queueable;
    protected $user_tweet ;

    /**
     * Create a new notification instance.
     */
    public function __construct($user_tweet)
    {
        $this->user_tweet = $user_tweet;
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
            'idUser' => $this->user_tweet->id ,
            'name' => $this->user_tweet->name ,
            'pseudo' => $this->user_tweet->pseudo ,
            'pp' => $this->user_tweet->extra_user->pp ?? null
        ];
    }
}
