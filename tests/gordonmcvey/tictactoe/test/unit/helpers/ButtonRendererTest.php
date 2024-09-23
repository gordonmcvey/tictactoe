<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\test\unit\helpers;

use gordonmcvey\tictactoe\enum\PlayerIds;
use gordonmcvey\tictactoe\enum\Tokens;
use gordonmcvey\tictactoe\helpers\ButtonRenderer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ButtonRendererTest extends TestCase
{
    #[DataProvider('provideData')]
    public function testRender(int $slot, ?PlayerIds $player, string $expectation): void
    {
        $button = (new ButtonRenderer())->renderButton($slot, $player?->value);
        $this->assertEquals($expectation, $button);
    }

    public static function provideData(): array
    {
        return [
            "Player 1 selected" => [
                "slot"        => 1,
                "player"      => PlayerIds::PLAYER_1,
                "expectation" => '<button disabled="disabled" type="submit" name="move" value="1">'
                  . Tokens::PLAYER_1_TOKEN->value
                  . '</button>',
            ],
            "Player 2 selected" => [
                "slot"        => 2,
                "player"      => PlayerIds::PLAYER_2,
                "expectation" => '<button disabled="disabled" type="submit" name="move" value="2">'
                    . Tokens::PLAYER_2_TOKEN->value
                    . '</button>',
            ],
            "No player selected" => [
                "slot"        => 3,
                "player"      => null,
                "expectation" => '<button type="submit" name="move" value="3">'
                    . Tokens::FREE_TOKEN->value
                    . '</button>',
            ],
        ];
    }
}
