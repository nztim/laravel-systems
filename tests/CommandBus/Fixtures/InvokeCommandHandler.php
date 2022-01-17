<?php

namespace NZTim\Tests\CommandBus\Fixtures;

class InvokeCommandHandler
{
    public function __invoke(InvokeCommand $command)
    {
        return $command->val();
    }
}
