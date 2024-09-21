<?php

declare(strict_types=1);

namespace gordonmcvey\tictactoe\interface;

interface PlayerContract
{
    public function play(): self;
}
