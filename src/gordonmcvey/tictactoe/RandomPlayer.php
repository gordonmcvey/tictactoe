<?php

namespace gordonmcvey\tictactoe;

use gordonmcvey\tictactoe\enum\PlayerIds;
use Random\Randomizer;

/**
 * Computer player that plays random moves
 */
class RandomPlayer extends AbstractPlayer
{
    public function __construct(
        PlayerIds                   $playerId,
        Board                       $board,
        private readonly Randomizer $randomiser,
    ) {
        parent::__construct($playerId, $board);
    }

    public function play(): self
    {
        $this->board->play($this->playerId, $this->computeMove());
        return $this;
    }

    /**
     * Randomly select a move from the available set
     */
    private function computeMove(): int
    {
        $availableMoves = $this->board->availableMoves();

        if (empty($availableMoves)) {
            throw new \RuntimeException("No available moves");
        }

        $picked = $this->randomiser->pickArrayKeys($availableMoves, 1)[0];
        return $availableMoves[$picked];
    }
}
