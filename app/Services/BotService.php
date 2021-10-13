<?php

namespace App\Services;

use App\Models\User;
use App\Services\MessagesService;
use App\Services\QuestionService;
use App\Events\ScoreUpdated;

class BotService
{
    const ROUND_TIME = 30;
    const HINTS_TIMING = [
        20 => 1,
        10 => 2,
    ];
    const SCORE_TIMING = [
        20 => 3,
        10 => 2,
        0 => 1,
    ];

    private QuestionService $question;
    private MessagesService $messages;

    private int $timer;

    public function __construct()
    {
        $this->question = new QuestionService(count(self::HINTS_TIMING));
        $this->messages = new MessagesService;
        $this->nextQuestion();
    }

    public function tick()
    {
        switch ($this->timer) {
            case 0:
                $this->noAnswer();
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
        $this->timer = self::ROUND_TIME;
        $this->question->random();
        $msg = 'Внимание, вопрос! ' . $this->question->question;
        $this->messages->storeSystemMessage($msg);
    }

    public function checkAnswer($answer): bool
    {
        if (!is_null($answer->user) && $answer->body === $this->question->answer) {
            $msg = $answer->user->name . ', верно! Ваш ответ "' . $this->question->answer . '" верный!';
            $this->messages->storeSystemMessage($msg);
            $this->awardUser($answer->user->id);
            $this->nextQuestion();
            return true;
        }
        return false;
    }

    public function awardUser(int $userId)
    {
        $reward = $this->currentReward();
        $user = User::find($userId);
        $user->score += $reward;
        $user->save();
        $msg = $user->name . ' заработал очков: ' . $reward;
        $this->messages->storeSystemMessage($msg);
        ScoreUpdated::dispatch($user);
    }

    public function currentReward(): int
    {
        foreach (self::SCORE_TIMING as $time => $score) {
            if ($this->timer >= $time) {
                return $score;
            }
        }
        return 0;
    }

}
