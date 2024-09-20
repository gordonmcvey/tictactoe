<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe;

use Random\Randomizer;

class Game
{
    public const int    PLAYER_1       = 1;
    public const int    PLAYER_2       = 2;
    public const string PLAYER_1_TOKEN = "️🅾️";
    public const string PLAYER_2_TOKEN = "❎";
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
            $this->board->play(self::PLAYER_1, $slot);
            if ($this->hasWon(self::PLAYER_1)) {
                return self::PLAYER_1;
            }
        }

        if ($movesRemaining > 1) {
            $this->board->play(self::PLAYER_2, $this->computeMove());
            if ($this->hasWon(self::PLAYER_2)) {
                return self::PLAYER_2;
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
