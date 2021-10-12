<?php

namespace App\Models;

class Hint
{

    public array $answer;
    public array $hintedKeys;
    public int $hintCount;

    public function __construct(array $answer, array $hintedKeys, int $hintCount)
    {
        $this->answer = $answer;
        $this->hintedKeys = $hintedKeys;
        $this->hintCount = $hintCount;
    }

    public function makeKeys(): array
    {
        $unhintedKeys = array_diff(array_keys($this->answer), $this->hintedKeys);
        if (!is_null($unhintedKeys) && count($unhintedKeys) >= $this->hintCount) {
            shuffle($unhintedKeys);
            $this->hintedKeys = array_merge(
                $this->hintedKeys,
                array_slice($unhintedKeys, 0, $this->hintCount)
            );
        }
        return $this->hintedKeys;
    }

    public function render(): array
    {
        $dummy = mb_str_split(str_repeat('_', count($this->answer)), 1, 'UTF-8');
        foreach ($this->hintedKeys as $key) {
            $dummy[$key] = $this->answer[$key];
        }
        return $dummy;
    }

}
