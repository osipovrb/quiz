<?php

namespace App\Services;

use App\Models\Question;
use App\Services\HintsService;
use Illuminate\Support\Facades\Redis;

class QuestionsService
{
    public function __construct(int $hintsCount = 2)
    {
        $this->hintsCount = $hintsCount;
        $this->init();
    }

    public function init(): void
    {
        if (! $this->loadFromRedis()) {
            $this->pickRandom();
        }
    }

    public function pickRandom(): QuestionsService
    {
        $this->loadRandomQuestion();
        $this->makeHints();
        $this->saveToRedis();
        return $this;
    }

    public function getHint(int $index): ?string
    {
        return Redis::get('hint'.$index);
    }

    private function loadFromRedis(): bool
    {
        if (! Redis::exists('question') || ! Redis::exists('answer')) {
            return false;
        }
        $this->question = Redis::get('question');
        $this->answer = Redis::get('answer');
        $this->iterateHints(function($hint) {
            $this->$hint = Redis::get($hint);
        });
        return true;
    }

    private function saveToRedis(): void
    {
        Redis::set('question', $this->question);
        Redis::set('answer', $this->answer);
        $this->iterateHints(function($hint) {
            Redis::set($hint, $this->$hint);
        });
    }

    private function loadRandomQuestion()
    {
        $question = Question::inRandomOrder()
            ->limit(1)
            ->get()
            ->first();
        $this->question = $question->question;
        $this->answer = $question->answer;
    }

    private function makeHints()
    {
        $hintsService = new HintsService($this->answer);
        $hints = $hintsService->make($this->hintsCount)->render();
        $this->iterateHints(function($hint, $i) use ($hints) {
            $this->$hint = $hints[$i - 1];
        });
    }

    private function iterateHints($function)
    {
        for ($i = 1; $i <= $this->hintsCount; $i++) {
            $hint = 'hint' . $i;
            $function($hint, $i);
        }
    }

}
