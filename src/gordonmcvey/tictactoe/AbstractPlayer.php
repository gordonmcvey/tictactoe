<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe;

use gordonmcvey\tictactoe\enum\Players;
use gordonmcvey\tictactoe\interface\PlayerContract;

abstract class AbstractPlayer implements PlayerContract
{
    public function __construct(
        public readonly Players $playerId,
        protected readonly Board $board,
    ) {}
}
