<?php

namespace NZTim\Tests\CommandBus\Fixtures;

class MissingDefaultCommandHandler
{
    public function execute(DefaultCommand $command): string
    {
        return $command->val();
    }
}
