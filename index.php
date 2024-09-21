<?php

use gordonmcvey\tictactoe\Board;
use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\Game;
use gordonmcvey\tictactoe\helpers\ButtonRenderer;
use gordonmcvey\tictactoe\HumanPlayer;
use gordonmcvey\tictactoe\RandomPlayer;
use Random\Randomizer;

require __DIR__ . '/vendor/autoload.php';

session_start();

if (isset($_POST["restart"])) {
    $_SESSION = [];
}

// Init
$buttons = new ButtonRenderer();
$board = isset($_SESSION["slots"]) ? new Board($_SESSION["slots"]) : new Board();
$player1 = new HumanPlayer(PlayerIds::PLAYER_1, $board);
$player2 = new RandomPlayer(PlayerIds::PLAYER_2, $board, new Randomizer());
$game = new Game(
    board: $board,
    player1: $player1,
    player2: $player2,
);

// Play
$winner = null;
if (isset($_POST["move"])){
    $player1->setMove($_POST["move"]);
    $game->playRound();
}

// Preserve state
$slots = $board->getSlots();
$_SESSION["slots"] = $slots;

?>
<html lang="en-gb">
<head>
    <title>Tic Tac Toe</title>
    <style>
        .game {
            display: flex;
            justify-content: center;
            background-color: antiquewhite;
            padding: 5px;
        }
        .grid {
            border: 1px solid;
        }
        .cell {
            width: 1.5em;
            height: 1.5em;
            text-align: center;
        }
    </style>
</head>
<body>
    <form action="/" method="post" class="game">
        <fieldset>
            <table class="grid">
                <tbody>
                <tr style="height: 1.5em">
                    <td class="cell"><?=$buttons->renderButton(0, $slots[0]) ?></td>
                    <td class="cell"><?=$buttons->renderButton(1, $slots[1]) ?></td>
                    <td class="cell"><?=$buttons->renderButton(2, $slots[2]) ?></td>
                </tr>
                <tr style="height: 1.5em">
                    <td class="cell"><?=$buttons->renderButton(3, $slots[3]) ?></td>
                    <td class="cell"><?=$buttons->renderButton(4, $slots[4]) ?></td>
                    <td class="cell"><?=$buttons->renderButton(5, $slots[5]) ?></td>
                </tr>
                <tr style="height: 1.5em">
                    <td class="cell"><?=$buttons->renderButton(6, $slots[6]) ?></td>
                    <td class="cell"><?=$buttons->renderButton(7, $slots[7]) ?></td>
                    <td class="cell"><?=$buttons->renderButton(8, $slots[8]) ?></td>
                </tr>
                </tbody>
            </table>
            <input style="display: block;" type="submit" name="restart" value="Restart game" />
        </fieldset>
    </form>
</body>
</html>
