<?php

namespace App\Services;

use App\Models\Question;
use App\Services\HintsService;

class QuestionService
{

    public string $question;
    public string $answer;
    public array $hints;

    private int $hintsCount;

    public function __construct(int $hintsCount = 2)
    {
        $this->hintsCount = $hintsCount;
    }

    public function random(): QuestionService
    {
        $this->loadRandomQuestion();
        $this->makeHints();
        return $this;
    }

    public function count(): int
    {
        return Question::count();
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
        $this->hints = $hintsService->make($this->hintsCount)->render();
    }

}
