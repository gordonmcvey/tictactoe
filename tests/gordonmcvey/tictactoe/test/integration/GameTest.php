<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\tests\integration;

use gordonmcvey\tictactoe\Board;
use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\Game;
use gordonmcvey\tictactoe\HumanPlayer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    #[DataProvider('provideData')]
    public function testGame(array $rounds, ?PlayerIds $expectedWinner, array $expectedBoard): void
    {
        // Setup
        $board = new Board();
        $players = [
            PlayerIds::PLAYER_1->value => new HumanPlayer(PlayerIds::PLAYER_1, $board),
            PlayerIds::PLAYER_2->value => new HumanPlayer(PlayerIds::PLAYER_2, $board),
        ];
        $game = new Game($board, $players[PlayerIds::PLAYER_1->value], $players[PlayerIds::PLAYER_2->value]);
        $winner = null;

        // Play game
        foreach ($rounds as $round) {
            foreach ($players as $playerId => $player) {
                isset($round[$playerId]) && $player->setMove($round[$playerId]);
            }
            $winner = $game->playRound();
            if (null !== $winner) {
                break;
            }
        }

        // Assert expectations
        $this->assertSame($expectedWinner->value, $winner);
        $this->assertEquals($expectedBoard, $board->getSlots());
    }

    public static function provideData(): array
    {
        return [
            "Player 1 wins top row"        => [
                "rounds"         => [
                    [
                        PlayerIds::PLAYER_1->value => 0,
                        PlayerIds::PLAYER_2->value => 3,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 1,
                        PlayerIds::PLAYER_2->value => 4,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 2,
                    ],
                ],
                "expectedWinner" => PlayerIds::PLAYER_1,
                "expectedBoard"  => [
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_2->value,
                    null,
                    null,
                    null,
                    null,
                ],
            ],
            "Player 2 wins middle row"     => [
                "rounds"         => [
                    [
                        PlayerIds::PLAYER_1->value => 0,
                        PlayerIds::PLAYER_2->value => 3,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 1,
                        PlayerIds::PLAYER_2->value => 4,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 8,
                        PlayerIds::PLAYER_2->value => 5,
                    ],
                ],
                "expectedWinner" => PlayerIds::PLAYER_2,
                "expectedBoard"  => [
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_2->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                ],
            ],
            "Player 1 wins bottom row"     => [
                "rounds"         => [
                    [
                        PlayerIds::PLAYER_1->value => 6,
                        PlayerIds::PLAYER_2->value => 0,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 7,
                        PlayerIds::PLAYER_2->value => 1,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 8,
                    ],
                ],
                "expectedWinner" => PlayerIds::PLAYER_1,
                "expectedBoard"  => [
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_2->value,
                    null,
                    null,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                ],
            ],
            "Player 2 wins left column"    => [
                "rounds"         => [
                    [
                        PlayerIds::PLAYER_1->value => 1,
                        PlayerIds::PLAYER_2->value => 0,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 4,
                        PlayerIds::PLAYER_2->value => 3,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 8,
                        PlayerIds::PLAYER_2->value => 6,
                    ],
                ],
                "expectedWinner" => PlayerIds::PLAYER_2,
                "expectedBoard"  => [
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    PlayerIds::PLAYER_2->value,
                    null,
                    PlayerIds::PLAYER_1->value,
                ],
            ],
            "Player 1 wins middle column"  => [
                "rounds"         => [
                    [
                        PlayerIds::PLAYER_1->value => 1,
                        PlayerIds::PLAYER_2->value => 0,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 4,
                        PlayerIds::PLAYER_2->value => 8,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 7,
                    ],
                ],
                "expectedWinner" => PlayerIds::PLAYER_1,
                "expectedBoard"  => [
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                ],
            ],
            "Player 2 wins right column"   => [
                "rounds"         => [
                    [
                        PlayerIds::PLAYER_1->value => 0,
                        PlayerIds::PLAYER_2->value => 2,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 3,
                        PlayerIds::PLAYER_2->value => 5,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 7,
                        PlayerIds::PLAYER_2->value => 8,
                    ],
                ],
                "expectedWinner" => PlayerIds::PLAYER_2,
                "expectedBoard"  => [
                    PlayerIds::PLAYER_1->value,
                    null,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    PlayerIds::PLAYER_2->value,
                    null,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                ],
            ],
            "Player 1 wins diagonal left"  => [
                "rounds"         => [
                    [
                        PlayerIds::PLAYER_1->value => 4,
                        PlayerIds::PLAYER_2->value => 3,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 0,
                        PlayerIds::PLAYER_2->value => 6,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 8,
                    ],
                ],
                "expectedWinner" => PlayerIds::PLAYER_1,
                "expectedBoard"  => [
                    PlayerIds::PLAYER_1->value,
                    null,
                    null,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    PlayerIds::PLAYER_2->value,
                    null,
                    PlayerIds::PLAYER_1->value,
                ],
            ],
            "Player 2 wins diagonal right" => [
                "rounds"         => [
                    [
                        PlayerIds::PLAYER_1->value => 3,
                        PlayerIds::PLAYER_2->value => 4,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 6,
                        PlayerIds::PLAYER_2->value => 0,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 5,
                        PlayerIds::PLAYER_2->value => 8,
                    ],
                ],
                "expectedWinner" => PlayerIds::PLAYER_2,
                "expectedBoard"  => [
                    PlayerIds::PLAYER_2->value,
                    null,
                    null,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    null,
                    PlayerIds::PLAYER_2->value,
                ],
            ],
            "Stalemate"                    => [
                "rounds"         => [
                    [
                        PlayerIds::PLAYER_1->value => 0,
                        PlayerIds::PLAYER_2->value => 1,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 2,
                        PlayerIds::PLAYER_2->value => 4,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 7,
                        PlayerIds::PLAYER_2->value => 6,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 5,
                        PlayerIds::PLAYER_2->value => 8,
                    ],
                    [
                        PlayerIds::PLAYER_1->value => 3,

                    ],
                ],
                "expectedWinner" => null,
                "expectedBoard"  => [
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                    PlayerIds::PLAYER_1->value,
                    PlayerIds::PLAYER_2->value,
                ],
            ],
        ];
    }
}
