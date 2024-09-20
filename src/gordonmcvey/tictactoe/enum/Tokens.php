<?php

declare (strict_types = 1);

namespace gordonmcvey\tictactoe\enum;

enum Tokens: string
{
    case PLAYER_1_TOKEN = "️🅾️";
    case PLAYER_2_TOKEN = "❎";
    case FREE_TOKEN     = "🆓";
}
