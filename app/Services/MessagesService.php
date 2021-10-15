<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class MessagesService
{
    public function getLastMessages(int $num = 30)
    {
        return Message::with(['user'])->latest()
            ->limit($num)
            ->get();
    }

    public function storeMessage(Request $request)
    {
        $msg = $request->user()->messages()->create([
            'body' => $request->body
        ]);
        Redis::publish(env('TIMER_CHANNEL', 'TIMER'), $msg->toRedis());
    }

    public function storeSystemMessage(string $body)
    {
        Message::create([
            'body' => $body
        ]);
    }
}
