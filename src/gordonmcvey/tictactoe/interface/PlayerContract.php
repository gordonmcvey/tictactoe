<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\interface;

use gordonmcvey\tictactoe\enum\PlayerIds;

/**
 * @property PlayerIds $playerId
 */
interface PlayerContract
{
    public function play(): self;

    public function getPlayerId(): PlayerIds;
}
