<?php

use Random\Randomizer;

class Board
{
    public function __construct(private ?array $slots = [
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
    ]) {}

    public function movesLeft(): int
    {
        return count($this->availableMoves());
    }

    public function availableMoves(): array
    {
        return array_keys(array_filter($this->slots, fn ($slot) => null === $slot));
    }

    public function getSlots(): array
    {
        return $this->slots;
    }

    public function play(int $player, int $move): self
    {
        if (null !== $this->slots[$move]) {
            throw new \InvalidArgumentException("Illegal move: Slot already occupied");
        }

        $this->slots[$move] = $player;
        return $this;
    }
}

class Game
{
    public const int PLAYER_1 = 1;
    public const int PLAYER_2 = 2;
    public const string PLAYER_1_TOKEN = "️🅾️";
    public const string PLAYER_2_TOKEN = "❎";
    private const array WIN_CONDITIONS = [
        [0, 1, 2], // Top row
        [3, 4, 5], // Middle row
        [6, 7, 8], // Bottom row
        [0, 3, 6], // Left column
        [1, 4, 7], // Middle column
        [2, 5, 8], // Right column
        [0, 4, 8], // Diagonal left
        [2, 4, 6], // Diagonal right
    ];

    public function __construct(public Board $board, private readonly Randomizer $randomiser) {
    }

    public function playRound(int $slot): ?int {
        $movesRemaining = $this->board->movesLeft();

        if ($movesRemaining > 0) {
            $this->board->play(self::PLAYER_1, $slot);
            if ($this->hasWon(self::PLAYER_1)) {
                return self::PLAYER_1;
            }
        }

        if ($movesRemaining > 1) {
            $this->board->play(self::PLAYER_2, $this->computeMove());
            if ($this->hasWon(self::PLAYER_2)) {
                return self::PLAYER_2;
            }
        }

        return null;
    }

    public function hasWon(int $player): bool
    {
        $slots = $this->board->getSlots();

        foreach (self::WIN_CONDITIONS as $winCondition) {
            if ($player === $slots[$winCondition[0]]
                && $player === $slots[$winCondition[1]]
                && $player === $slots[$winCondition[2]]) {
                return true;
            }
        }

        return false;
    }

    private function computeMove(): int
    {
        // This is where we'd implement the computer player's logic, but we don't have time for that
        // so we'll just make the computer play a random move
        $availableMoves = $this->board->availableMoves();

        if (empty($availableMoves)) {
            throw new RuntimeException("No available moves");
        }

        $picked = $this->randomiser->pickArrayKeys($availableMoves, 1)[0];
        return $availableMoves[$picked];
    }
}

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
