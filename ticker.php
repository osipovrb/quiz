<?php

class Ticker
{
    const TICK_MESSAGE = 'beep boop';
    const DOTENV_FILE = '.env';
    const DOTENV_CONFIG = [
        'REDIS_HOST',
        'REDIS_PORT',
        'REDIS_PASSWORD',
        'REDIS_PREFIX',
        'TIMER_CHANNEL',
    ];

    public function __construct()
    {
        $env = file_get_contents(self::DOTENV_FILE);
        foreach (self::DOTENV_CONFIG as $key) {
            preg_match('/'.$key.'=([\S]+)(\s|$)/', $env, $match);
            if (array_key_exists(1, $match)) {
                $this->config[$key] = ($match[1] === 'null') ? null : $match[1];
            }
        }
    }

    public function startTick()
    {
        $start = microtime(true);

        $redis = new Redis();
        $redis->pconnect($this->config['REDIS_HOST'], $this->config['REDIS_PORT']);
        $redis->auth($this->config['REDIS_PASSWORD']);
        $channel = $this->config['REDIS_PREFIX'].$this->config['TIMER_CHANNEL'];

        for ($i = 1; true; ++$i) {
            $redis->publish($channel, self::TICK_MESSAGE);
            time_sleep_until($start + $i);
        }

        $redis->close();
    }
}

(new Ticker)->startTick();
