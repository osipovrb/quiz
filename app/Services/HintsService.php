<?php

namespace App\Services;

use App\Models\Hint;

class HintsService
{

    public array $hints;

    private array $answer;
    private array $hintedKeys;

    public function __construct(string $answer)
    {
        $this->answer = mb_str_split($answer);

        $this->hintedKeys = [];
        $this->hints = [];

        $this->hintWhitespaces();
    }

    public function make(int $hintsCount = 2): HintsService
    {
        $hintingLettersCount = ceil(count($this->answer) * 0.5 / $hintsCount);
        for ($i = 0; $i < $hintsCount; $i++) {
            $hint = new Hint($this->answer, $this->hintedKeys, $hintingLettersCount);
            $this->hintedKeys = $hint->makeKeys();
            $this->hints[] = $hint->render();
        }
        return $this;
    }

    public function render(): array
    {
        return array_map(fn($v) => implode('', $v), $this->hints);
    }

    private function hintWhitespaces(): void
    {
        $this->hintedKeys = array_keys(
            array_filter($this->answer, fn($v) => ($v === ' '))
        );
    }

}
