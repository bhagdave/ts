<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StreamUpdated implements ShouldBroadcast
{
use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $user;
    public $message;
    public $link;
    public $name;
    public $description;

    public function __construct($user, $id, $message)
    {
        $this->id = $id;
        $this->message = $message;
        $this->user = $user;
        $this->link = 'stream/' . $id;
        $this->name = $user->firstName . ' ' . $user->lastName;
        $this->description = $message;
    }

    public function broadcastOn()
    {
      return new PrivateChannel('stream.'.$this->id);
    }
}
