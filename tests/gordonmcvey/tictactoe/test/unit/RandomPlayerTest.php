<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\tests\unit;

use gordonmcvey\tictactoe\Board;
use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\RandomPlayer;
use PHPUnit\Framework\TestCase;
use Random\Randomizer;

class RandomPlayerTest extends TestCase
{
    public function testPlay(): void
    {
        $board = $this->createMock(Board::class);
        $board->method("availableMoves")->willReturn([4]);

        // Glass box: Player 1 should play slot 4 (as it's the only move)
        $board->expects($this->once())->method("play")->with(PlayerIds::PLAYER_1, 4)->willReturnSelf();

        $player = new RandomPlayer(PlayerIds::PLAYER_1, $board, new Randomizer());
        $player->play();
    }
}
