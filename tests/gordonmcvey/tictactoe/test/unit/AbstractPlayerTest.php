<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\test\unit;

use gordonmcvey\tictactoe\AbstractPlayer;
use gordonmcvey\tictactoe\Board;
use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\interface\PlayerContract;
use PHPUnit\Framework\TestCase;

class AbstractPlayerTest extends TestCase
{
    public function testGetPlayer(): void
    {
        $player = new class(PlayerIds::PLAYER_1, $this->createMock(Board::class)) extends AbstractPlayer {

            public function play(): PlayerContract {
                return $this;
            }
        };

        $this->assertSame(PlayerIds::PLAYER_1, $player->getPlayerId());
    }
}
