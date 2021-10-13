<?php

namespace App\Observers;

use App\Models\Message;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\Redis;

class MessageObserver
{
    public $afterCommit = true;

    public function created(Message $message)
    {
        broadcast(new MessageCreated($message));
    }
}
