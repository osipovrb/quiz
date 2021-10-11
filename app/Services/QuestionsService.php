<?php

namespace App\Services;

use App\Models\Question;
use Illuminate\Support\Facades\Redis;

class QuestionsService
{

    public function setRandomQuestion(): array
    {
        $question = Question::inRandomOrder()->limit(1)->get()->first();
        Redis::set('current_question', $question->question);
        Redis::set('current_answer', $question->answer);
        $hints = $this->generateHints($question->answer);
        Redis::set('hint1', $hints[0]);
        Redis::set('hint2', $hints[1]);
        return [$question->question, $question->answer];
    }

    public function getCurrentQuestion(): array
    {
        $question = Redis::get('current_question');
        $answer = Redis::get('current_answer');
        return (is_null($question) || is_null(($answer)))
            ? $this->setRandomQuestion()
            : [$question, $answer];
    }

    public function getHint(int $index): ?string
    {
        return Redis::get('hint'.$index);
    }

    public function generateHints(string $answer): array
    {

    }

}
