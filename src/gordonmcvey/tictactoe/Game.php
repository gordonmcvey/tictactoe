<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe;

use gordonmcvey\tictactoe\interface\PlayerContract;

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

    /**
     * @var array<array-key, PlayerContract>
     */
    private readonly array $players;

    public function __construct(
        private readonly Board $board,
        PlayerContract         $player1,
        PlayerContract         $player2,
    ) {
        $this->players = [$player1, $player2];
    }

    public function playRound(): ?int
    {
        $movesRemaining = $this->board->movesLeft();

        /** @var AbstractPlayer $player */
        foreach ($this->players as $player) {
            // Guard to prevent further play should all available moves get played before the end of the round
            if ($movesRemaining-- <= 0) {
                break;
            }

            if ($this->hasWon($player->play())) {
                return $player->getPlayerId()->value;
            }
        }

        return null;
    }

    public function hasWon(PlayerContract $player): bool
    {
        $slots = $this->board->getSlots();
        $playerId = $player->getPlayerId()->value;

        foreach (self::WIN_CONDITIONS as $winCondition) {
            if (
                $playerId === $slots[$winCondition[0]]
                && $playerId === $slots[$winCondition[1]]
                && $playerId === $slots[$winCondition[2]]
            ) {
                return true;
            }
        }

        return false;
    }
}
