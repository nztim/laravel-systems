<?php

namespace NZTim\Tests\CommandBus\Fixtures;

class InvokeCommand
{
    public function val(): string
    {
        return 'invoked!';
    }
}
