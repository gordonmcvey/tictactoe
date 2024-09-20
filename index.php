<?php

use gordonmcvey\tictactoe\Board;
use gordonmcvey\tictactoe\Game;
use Random\Randomizer;

require __DIR__ . '/vendor/autoload.php';

function renderButton(int $slot, ?int $player): string {
    $symbol = match ($player) {
        Game::PLAYER_1 => Game::PLAYER_1_TOKEN,
        Game::PLAYER_2 => Game::PLAYER_2_TOKEN,
        default => "🆓",
    };

    $disable = null !== $player ?
        'disabled="disabled"' :
        '';

    return sprintf(
        '<button %s type="submit" name="move" value="%d">%s</button>',
        $disable,
        $slot,
        $symbol,
    );
}

session_start();

if (isset($_POST["restart"])) {
    $_SESSION = [];
}

// Init
$game = new Game(isset($_SESSION["slots"]) ? new Board($_SESSION["slots"]) : new Board(), new Randomizer());

// Play
$winner = isset($_POST["move"]) ? $game->playRound($_POST["move"]) : null;

// Preserve state
$slots = $game->board->getSlots();
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
                    <td class="cell"><?=renderButton(0, $slots[0]) ?></td>
                    <td class="cell"><?=renderButton(1, $slots[1]) ?></td>
                    <td class="cell"><?=renderButton(2, $slots[2]) ?></td>
                </tr>
                <tr style="height: 1.5em">
                    <td class="cell"><?=renderButton(3, $slots[3]) ?></td>
                    <td class="cell"><?=renderButton(4, $slots[4]) ?></td>
                    <td class="cell"><?=renderButton(5, $slots[5]) ?></td>
                </tr>
                <tr style="height: 1.5em">
                    <td class="cell"><?=renderButton(6, $slots[6]) ?></td>
                    <td class="cell"><?=renderButton(7, $slots[7]) ?></td>
                    <td class="cell"><?=renderButton(8, $slots[8]) ?></td>
                </tr>
                </tbody>
            </table>
            <input style="display: block;" type="submit" name="restart" value="Restart game" />
        </fieldset>
    </form>
</body>
</html>
