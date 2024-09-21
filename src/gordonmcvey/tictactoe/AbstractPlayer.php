<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe;

use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\interface\PlayerContract;

abstract class AbstractPlayer implements PlayerContract
{
    public function __construct(
        private readonly PlayerIds $playerId,
        protected readonly Board   $board,
    ) {
    }

    public function getPlayerId(): PlayerIds
    {
        return $this->playerId;
    }
}
