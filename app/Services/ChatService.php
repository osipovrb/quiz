<?php

namespace App\Services;

use Pusher\Pusher;
use Exception;

class ChatService
{

    private Pusher $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            config('broadcasting.connections.pusher.options')
        );
    }

    public function isEmpty(): bool
    {
        try {
            $subscribers = $this->pusher->get('/channels/presence-chat/users')->users;
            return (count($subscribers) === 0);
        } catch (Exception $e) {
            return true; // если к каналу никто не подключен, то вызывается исключение
        }
    }
}
