<?php

namespace NZTim\Tests\CommandBus\Fixtures;

class DefaultCommand
{
    public function val(): string
    {
        return 'default!';
    }
}
