<?php

namespace App\Listeners;

use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;

class BroadcastMessage implements ShouldBroadcastNow
{
    use SerializesModels;

    public Message $message;
    public User $user;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->user = $this->message->user;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('chat');
    }

    public function broadcastWith()
    {
        return 'hui';
    }

}
