<?php

namespace App\Console\Commands;

use App\Services\BotService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Pusher\Pusher;

class Tick extends Command
{
    protected $signature = 'ticker:listen';
    protected $description = 'Start listening to external ticker';
    private BotService $bot;

    public function __construct()
    {
        parent::__construct();
        $this->bot = new BotService();
    }

    public function handle()
    {
        $bot = $this->bot;
        Redis::subscribe([env('TIMER_CHANNEL', 'TIMER')], function ($message) use ($bot) {
            if (str_starts_with($message, 'answer:')) {
                $answer = explode(':', $message, 2)[1];
                $bot->checkAnswer(json_decode($answer));
            } else {
                $bot->tick();
            }
        });
    }

}
