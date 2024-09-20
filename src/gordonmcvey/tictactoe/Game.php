<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe;

use gordonmcvey\tictactoe\enum\Players;
use Random\Randomizer;

class Game
{
    private const array WIN_CONDITIONS = [
        [0, 1, 2], // Top row
        [3, 4, 5], // Middle row
        [6, 7, 8], // Bottom row
        [0, 3, 6], // Left column
        [1, 4, 7], // Middle column
        [2, 5, 8], // Right column
        [0, 4, 8], // Diagonal left
        [2, 4, 6], // Diagonal right
    ];

    public function __construct(public Board $board, private readonly Randomizer $randomiser)
    {
    }

    public function playRound(int $slot): ?int
    {
        $movesRemaining = $this->board->movesLeft();

        if ($movesRemaining > 0) {
            $this->board->play(Players::PLAYER_1->value, $slot);
            if ($this->hasWon(Players::PLAYER_1->value)) {
                return Players::PLAYER_1->value;
            }
        }

        if ($movesRemaining > 1) {
            $this->board->play(Players::PLAYER_2->value, $this->computeMove());
            if ($this->hasWon(Players::PLAYER_2->value)) {
                return Players::PLAYER_2->value;
            }
        }

        return null;
    }

    public function hasWon(int $player): bool
    {
        $slots = $this->board->getSlots();

        foreach (self::WIN_CONDITIONS as $winCondition) {
            if ($player === $slots[$winCondition[0]]
                && $player === $slots[$winCondition[1]]
                && $player === $slots[$winCondition[2]]) {
                return true;
            }
        }

        return false;
    }

    private function computeMove(): int
    {
        // This is where we'd implement the computer player's logic, but we don't have time for that
        // so we'll just make the computer play a random move
        $availableMoves = $this->board->availableMoves();

        if (empty($availableMoves)) {
            throw new \RuntimeException("No available moves");
        }

        $picked = $this->randomiser->pickArrayKeys($availableMoves, 1)[0];
        return $availableMoves[$picked];
    }
}
