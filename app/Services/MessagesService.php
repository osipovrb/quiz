<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Http\Request;

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
        return $request->user()->messages()->create([
            'body' => $request->body
        ]);
    }

    public function storeSystemMessage(string $body)
    {
        return Message::create([
            'body' => $body
        ]);
    }
}
