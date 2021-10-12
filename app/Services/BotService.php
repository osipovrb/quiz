<?php

namespace App\Services;

use App\Services\MessagesService;
use App\Services\QuestionService;

class BotService
{
    const ROUND_TIME = 30;
    const HINTS_TIMING = [
        20 => 1,
        10 => 2,
    ];

    private QuestionService $question;
    private MessagesService $messages;

    private int $timer;

    public function __construct()
    {
        $this->question = new QuestionService(count(self::HINTS_TIMING));
        $this->messages = new MessagesService;
        $this->timer = self::ROUND_TIME;
    }

    public function tick()
    {
        switch ($this->timer) {
            case 0:
                $this->noAnswer();
                $this->timer = self::ROUND_TIME;
                break;
            case (in_array($this->timer, array_keys(self::HINTS_TIMING))):
                $this->hint(self::HINTS_TIMING[$this->timer]);
                break;
        }
        $this->timer--;
    }

    public function noAnswer()
    {
        $msg = 'Правильный ответ: ' . $this->question->answer . '. Никто не ответил правильно. Переходим к следующему вопросу...';
        $this->messages->storeSystemMessage($msg);
        $this->nextQuestion();
    }

    public function hint(int $hintNum)
    {
        $msg = 'Подсказка: ' . $this->question->hints[$hintNum - 1];
        $this->messages->storeSystemMessage($msg);
    }

    public function nextQuestion()
    {
        $this->question->random();
        $msg = 'Внимание, вопрос! ' . $this->question->question;
        $this->messages->storeSystemMessage($msg);
    }

}
