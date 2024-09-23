<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\helpers;

use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\enum\Tokens;

class ButtonRenderer
{
    public function renderButton(int $slot, ?int $player): string
    {
        $symbol = match ($player) {
            PlayerIds::PLAYER_1->value => Tokens::PLAYER_1_TOKEN,
            PlayerIds::PLAYER_2->value => Tokens::PLAYER_2_TOKEN,
            default => Tokens::FREE_TOKEN,
        };

        $disable = null !== $player ?
            ' disabled="disabled"' :
            '';

        return sprintf(
            '<button%s type="submit" name="move" value="%d">%s</button>',
            $disable,
            $slot,
            $symbol->value,
        );
    }
}
