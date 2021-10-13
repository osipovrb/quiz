<?php

namespace App\Console\Commands;

use App\Services\BotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class Tick extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticker:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start listening to external ticker';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bot = new BotService();
        Redis::subscribe([env('TIMER_CHANNEL', 'TIMER')], function ($message) use ($bot) {
            if (str_starts_with($message, 'answer:')) {
                $msg = explode(':', $message, 2)[1];
                $bot->checkAnswer(json_decode($msg));
            } else {
                $bot->tick();
            }
        });
    }
}
