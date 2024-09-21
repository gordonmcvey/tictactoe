<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe;

use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\interface\PlayerContract;

abstract class AbstractPlayer implements PlayerContract
{
    public function __construct(
        public readonly PlayerIds $playerId,
        protected readonly Board  $board,
    ) {}
}
