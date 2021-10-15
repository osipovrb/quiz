<?php

namespace App\Services;

use App\Models\User;
use App\Services\MessagesService;
use App\Services\QuestionService;
use App\Services\ChatService;
use App\Events\ScoreUpdated;

class BotService
{
    const ROUND_TIME = 30;
    const WAIT_TIME = 3;
    const HINTS_TIMING = [
        20 => 1,
        10 => 2,
    ];
    const SCORE_TIMING = [
        20 => 3,
        10 => 2,
        0 => 1,
    ];

    const STATE_WAITING = 0;
    const STATE_QUESTION = 1;
    const STATE_STOPPED = 2;

    public int $state;

    private QuestionService $question;
    private MessagesService $messages;
    private ChatService $chat;

    private int $timer;

    public function __construct()
    {
        $this->question = new QuestionService(count(self::HINTS_TIMING));
        $this->messages = new MessagesService;
        $this->chat = new ChatService;
        $this->state = self::STATE_STOPPED;
        $this->timer = -1;
    }

    public function tick()
    {
        $chatEmpty = $this->chat->isEmpty();
        if ($chatEmpty && !$this->isStopped()) {
            $this->stopBot();
        } elseif (!$chatEmpty && $this->isStopped()) {
            $this->startBot();
        } elseif ($this->isWaiting() && $this->timer === 0) {
            $this->nextQuestion();
        } elseif ($this->isQuestion() && $this->timer === 0) {
            $this->noAnswer();
        } elseif ($this->isQuestion() && in_array($this->timer, array_keys(self::HINTS_TIMING))) {
            $this->hint(self::HINTS_TIMING[$this->timer]);
        }
        $this->timer--;
    }

    public function startBot()
    {
        $this->messages->storeSystemMessage('Добро пожаловать на викторину! Вопросов загружено: ' . $this->question->count());
        $this->waitTillNextQuestion();
    }

    public function stopBot()
    {
        $this->state = self::STATE_STOPPED;
        $this->messages->storeSystemMessage('Никого нет в чате. Бот остановлен.');
    }

    public function waitTillNextQuestion()
    {
        $this->state = self::STATE_WAITING;
        $this->timer = self::WAIT_TIME;
        $msg = 'Следующий вопрос через ' . $this->timer . ' сек...';
        $this->messages->storeSystemMessage($msg);
    }

    public function nextQuestion()
    {
        $this->state = self::STATE_QUESTION;
        $this->timer = self::ROUND_TIME;
        $this->question->random();
        $msg = 'Внимание, вопрос! ' . $this->question->question;
        $this->messages->storeSystemMessage($msg);
    }

    public function noAnswer()
    {
        $msg = 'Правильный ответ: ' . $this->question->answer . '. Никто не ответил правильно.';
        $this->messages->storeSystemMessage($msg);
        $this->waitTillNextQuestion();
    }

    public function hint(int $hintNum)
    {
        $msg = 'Подсказка: ' . $this->question->hints[$hintNum - 1];
        $this->messages->storeSystemMessage($msg);
    }

    public function checkAnswer($answer): bool
    {
        if (!is_null($answer->user) && $answer->body === $this->question->answer) {
            $msg = $answer->user->name . ', верно! Ваш ответ "' . $this->question->answer . '" верный!';
            $this->messages->storeSystemMessage($msg);
            $this->awardUser($answer->user->id);
            $this->waitTillNextQuestion();
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

    private function isStopped() { return $this->state === self::STATE_STOPPED; }
    private function isWaiting() { return $this->state === self::STATE_WAITING; }
    private function isQuestion() { return $this->state === self::STATE_QUESTION; }

}
