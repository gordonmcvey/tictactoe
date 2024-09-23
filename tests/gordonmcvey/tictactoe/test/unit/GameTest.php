<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\test\unit;

use gordonmcvey\tictactoe\Board;
use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\Game;
use gordonmcvey\tictactoe\interface\PlayerContract;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testPlayRound(): void
    {
        $board = $this->buildBoard([
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        ], 2);
        $player1 = $this->buildPlayer(PlayerIds::PLAYER_1);
        $player2 = $this->buildPlayer(PlayerIds::PLAYER_2);

        // Glass box: Each player is expected to each play this round as there are moves left and neither will win
        $player1->expects($this->once())->method("play");
        $player2->expects($this->once())->method("play");

        $game = new Game($board, $player1, $player2);

        $this->assertNull($game->playRound());
    }

    public function testPlayRoundOneMoveLeft(): void
    {
        $board = $this->buildBoard([
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        ], 1);
        $player1 = $this->buildPlayer(PlayerIds::PLAYER_1);
        $player2 = $this->buildPlayer(PlayerIds::PLAYER_2);

        // Glass box: Player 2 can't play because player 1 plays the last move
        $player1->expects($this->once())->method("play");
        $player2->expects($this->never())->method("play");

        $game = new Game($board, $player1, $player2);

        $this->assertNull($game->playRound());
    }

    public function testPlayRoundNoMovesLeft(): void
    {
        $board = $this->buildBoard([
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        ], 0);
        $player1 = $this->buildPlayer(PlayerIds::PLAYER_1);
        $player2 = $this->buildPlayer(PlayerIds::PLAYER_2);

        // Glass box: Neither player can play as there are no moves left
        $player1->expects($this->never())->method("play");
        $player2->expects($this->never())->method("play");

        $game = new Game($board, $player1, $player2);

        $this->assertNull($game->playRound());
    }

    public function testPlayRoundPlayer1wins(): void
    {
        $board = $this->buildBoard([
            0 => PlayerIds::PLAYER_1->value,
            null,
            null,
            null,
            4 => PlayerIds::PLAYER_1->value,
            null,
            null,
            null,
            8 => PlayerIds::PLAYER_1->value,
        ], 2);
        $player1 = $this->buildPlayer(PlayerIds::PLAYER_1);
        $player2 = $this->buildPlayer(PlayerIds::PLAYER_2);

        // Glass box: Player 2 can't play because player 1 wins
        $player1->expects($this->once())->method("play");
        $player2->expects($this->never())->method("play");

        $game = new Game($board, $player1, $player2);

        $this->assertSame(PlayerIds::PLAYER_1->value, $game->playRound());
    }

    #[DataProvider('provideWinningBoards')]
    public function testHasWon(array $slots): void
    {
        $player1 = $this->buildPlayer(PlayerIds::PLAYER_1);
        $player2 = $this->buildPlayer(PlayerIds::PLAYER_2);
        $game = new Game($this->buildBoard($slots), $player1, $player2);

        $this->assertTrue($game->hasWon($player1));
    }

    #[DataProvider('provideNonWinningBoards')]
    public function testHasNotWon(array $slots): void
    {
        $player1 = $this->buildPlayer(PlayerIds::PLAYER_1);
        $player2 = $this->buildPlayer(PlayerIds::PLAYER_2);
        $game = new Game($this->buildBoard($slots), $player1, $player2);

        $this->assertFalse($game->hasWon($player1));
    }

    public static function provideWinningBoards(): array
    {
        return [
            "Top row"        => [
                "slots" => [
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                ],
            ],
            "Middle row"     => [
                "slots" => [
                    null,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    null,
                ],
            ],
            "Bottom row"     => [
                "slots" => [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                ],
            ],
            "Left column"    => [
                "slots" => [
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                ],
            ],
            "middle column"  => [
                "slots" => [
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                ],
            ],
            "Right column"   => [
                "slots" => [
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                ],
            ],
            "Diagonal left"  => [
                "slots" => [
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                ],
            ],
            "Diagonal right" => [
                "slots" => [
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                ],
            ],
        ];
    }

    public static function provideNonWinningBoards(): array
    {
        return [
            "Empty board"        => [
                "slots" => [
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
            ],
            "Game in progress"   => [
                "slots" => [
                    null,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    null,
                    null,
                    null,
                    null,
                ],
            ],
            "Game in progress 2" => [
                "slots" => [
                    null,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    null,
                ],
            ],
            "Game in progress 3" => [
                "slots" => [
                    null,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    null,
                    null,
                ],
            ],
            "Stalemate"    => [
                "slots" => [
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                ],
            ],
        ];
    }

    private function buildPlayer(PlayerIds $playerId): PlayerContract&MockObject
    {
        $player = $this->createMock(PlayerContract::class);

        $player->method('play')->willreturnSelf();
        $player->method('getPlayerId')->willReturn($playerId);

        return $player;
    }

    private function buildBoard(array $slots, int $movesLeft = 9): Board&MockObject
    {
        $board = $this->createMock(Board::class);

        $board->method("play")->willReturnSelf();
        $board->method("movesLeft")->willReturn($movesLeft);
        $board->method("getSlots")->willReturn($slots);

        return $board;
    }
}
