<?php namespace NZTim\CommandBus;

interface Middleware
{
    /** @return mixed */
    public function execute(object $command, callable $next);
}
