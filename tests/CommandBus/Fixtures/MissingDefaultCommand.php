<?php

namespace NZTim\Tests\CommandBus\Fixtures;

class MissingDefaultCommand
{
    public function val(): string
    {
        return 'missing invoke!';
    }
}
