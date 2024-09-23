<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\test\unit;

use gordonmcvey\tictactoe\Board;
use gordonmcvey\tictactoe\enum\PlayerIds;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    public function testPlay(): void
    {
        $board = new Board();
        $this->assertSame(
            [
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
            ],
            $board->getSlots()
        );

        $board->play(PlayerIds::PLAYER_1, 5);
        $this->assertSame(
            [
                null,
                null,
                null,
                null,
                null,
                PlayerIds::PLAYER_1->value,
                null,
                null,
                null,
            ],
            $board->getSlots())
        ;
    }

    public function testPlayForAlreadyPlayedSlot(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $board = new Board();

        $board->play(PlayerIds::PLAYER_1, 5);
        $board->play(PlayerIds::PLAYER_1, 5);
    }

    public function testMovesLeft(): void
    {
        $board = new Board();

        $this->assertSame(9, $board->movesLeft());
        $board->play(PlayerIds::PLAYER_1, 0);
        $board->play(PlayerIds::PLAYER_2, 1);
        $board->play(PlayerIds::PLAYER_1, 2);
        $this->assertSame(6, $board->movesLeft());
        $board->play(PlayerIds::PLAYER_2, 3);
        $board->play(PlayerIds::PLAYER_1, 4);
        $board->play(PlayerIds::PLAYER_2, 5);
        $this->assertSame(3, $board->movesLeft());
        $board->play(PlayerIds::PLAYER_1, 6);
        $board->play(PlayerIds::PLAYER_2, 7);
        $board->play(PlayerIds::PLAYER_1, 8);
        $this->assertSame(0, $board->movesLeft());
    }

    public function testAvailableMoves(): void
    {
        $board = new Board([
            null,
            PlayerIds::PLAYER_1,
            null,
            PlayerIds::PLAYER_1,
            null,
            PlayerIds::PLAYER_1,
            null,
            PlayerIds::PLAYER_1,
            null,
        ]);

        $this->assertSame([0, 2, 4, 6, 8], $board->availableMoves());
    }
}
