<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\User;
use App\Message;

class DirectMessage extends Notification implements ShouldQueue
{
    use Queueable;

    private $fromUser;
    private $message;

    public function __construct(User $user, Message $message)
    {
        $this->fromUser = $user;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return [
            'database',
            'broadcast'
        ];
    }
    public function broadcastAs()
    {
        return 'DirectMessage';
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('You have a new direct message.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        return $this->toArray($notifiable);
    }
    public function toBroadcast($nofiable)
    {
        return new BroadcastMessage($this->toArray($nofiable));
    }
    public function toArray($notifiable)
    {
        return [
            'from' => $this->fromUser->firstName . ' ' . $this->fromUser->lastName,
            'from_sub' => $this->fromUser->sub,
            'message' => $this->message->message,
            'message_id' => $this->message->id,
            'link' => 'directmessage/' . $this->message->creator_type . '/' . $this->message->creator_type_id,
            'updated_at' => $this->message->updated_at
        ];
    }
}
