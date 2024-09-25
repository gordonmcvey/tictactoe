<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\tests\unit;

use gordonmcvey\tictactoe\Board;
use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\HumanPlayer;
use PHPUnit\Framework\TestCase;

class HumanPlayerTest extends TestCase
{
    public function testPlay(): void
    {
        $board = $this->createMock(Board::class);

        // Glass box: Player 1 should play slot 4
        $board->expects($this->once())->method("play")->with(PlayerIds::PLAYER_1, 4)->willReturnSelf();

        $player = new HumanPlayer(PlayerIds::PLAYER_1, $board);
        $player->setMove(4);

        $player->play();
    }

    public function testPlayMoveNotSpecified(): void
    {
        $board = $this->createMock(Board::class);

        // Glass box: No move should be played as none has been specified
        $this->expectException(\RuntimeException::class);
        $board->expects($this->never())->method("play")->willReturnSelf();

        $player = new HumanPlayer(PlayerIds::PLAYER_1, $board);

        $player->play();
    }

    public function testPlayMoveClearsSpecifiedMove(): void
    {
        $board = $this->createMock(Board::class);

        // Glass box: Player 1 should play slot 4
        $board->expects($this->once())->method("play")->with(PlayerIds::PLAYER_1, 4)->willReturnSelf();

        $player = new HumanPlayer(PlayerIds::PLAYER_1, $board);
        $player->setMove(4);

        // As playing a move clears it, the second call to play() should trigger an exception
        $player->play();
        $this->expectException(\RuntimeException::class);
        $player->play();
    }
}
