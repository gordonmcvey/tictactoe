<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe;

/**
 * Human-controlled player
 */
class HumanPlayer extends AbstractPlayer
{
    private ?int $move;

    public function play(): self
    {
        if (null === $this->move) {
            throw new \RuntimeException('Move not specified');
        }

        $this->board->play($this->playerId, $this->move);
        return $this;
    }

    public function setMove(int $move): self
    {
        $this->move = $move;
        return $this;
    }
}
