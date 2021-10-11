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
        $letters = mb_str_split($answer);
        $lettersIndexes = range(0, mb_strlen($answer, 'UTF-8') - 1);
        $lettersCount = sizeof($lettersIndexes);
        $hint1 = mb_str_split(str_repeat('_', $lettersCount), 1, 'UTF-8');
        $hint2 = mb_str_split(str_repeat('_', $lettersCount), 1, 'UTF-8');
        $hintCount = ceil($lettersCount * 20 / 100);
        shuffle($letters);
        for ($i = 0; $i <= $hintCount; $i++) {
            $letterToHint1 = array_pop($lettersIndexes);
            $letterToHint2 = array_shift($lettersIndexes);
            if (!$letterToHint1) {
                continue;
            }
            $hint1[$letterToHint1] = $letters[$letterToHint1];
            $hint2[$letterToHint1] = $letters[$letterToHint1];
            if (!$letterToHint2) {
                continue;
            }
            $hint2[$letterToHint2] = $letters[$letterToHint2];
        }
        var_dump($hint1, $hint2);
        return [implode('', $hint1), implode('', $hint2)];
    }

}
