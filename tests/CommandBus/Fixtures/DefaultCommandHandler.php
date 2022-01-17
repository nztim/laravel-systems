<?php

namespace NZTim\Tests\CommandBus\Fixtures;

class DefaultCommandHandler
{
    public function handle(DefaultCommand $command): string
    {
        return $command->val();
    }
}
