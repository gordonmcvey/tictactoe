<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe;

use gordonmcvey\tictactoe\enum\PlayerIds;

class Board
{
    /**
     * @param array<array-key, int|null>|null $slots
     */
    public function __construct(private ?array $slots = [
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
    ])
    {
    }

    public function movesLeft(): int
    {
        return count($this->availableMoves());
    }

    /**
     * @return array<array-key, int>
     */
    public function availableMoves(): array
    {
        return array_keys(array_filter($this->slots, fn($slot) => null === $slot));
    }

    /**
     * @return array<array-key, int|null>
     */
    public function getSlots(): array
    {
        return $this->slots;
    }

    public function play(PlayerIds $player, int $move): self
    {
        if (null !== $this->slots[$move]) {
            throw new \InvalidArgumentException("Illegal move: Slot already occupied");
        }

        $this->slots[$move] = $player->value;
        return $this;
    }
}
