<?php

namespace App\Observers;

use App\Models\Message;
use App\Events\MessageCreated;

class MessageObserver
{
    public $afterCommit = true;

    public function created(Message $message)
    {
        broadcast(new MessageCreated($message));
    }
}
