<?php

namespace NZTim\Tests\CommandBus\Fixtures;

class MissingMethodCommandHandler
{
    public function execute(DefaultCommand $command): string
    {
        return $command->val();
    }
}
