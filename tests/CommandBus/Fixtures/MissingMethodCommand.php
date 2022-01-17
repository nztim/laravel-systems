<?php

namespace NZTim\Tests\CommandBus\Fixtures;

class MissingMethodCommand
{
    public function val(): string
    {
        return 'missing!';
    }
}
