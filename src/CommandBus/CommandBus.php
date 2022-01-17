<?php

namespace NZTim\CommandBus;

class CommandBus
{
    /** @var callable */
    private $middlewareChain;

    public function __construct(Middleware ...$middleware)
    {
        $this->middlewareChain = $this->createExecutionChain($middleware);
    }

    /** @return mixed */
    public function handle(object $command)
    {
        return ($this->middlewareChain)($command);
    }

    /**
     * @param Middleware[]|array $middlewareList
     * @return callable
     */
    private function createExecutionChain(array $middlewareList): callable
    {
        $lastCallable = static function () : void {}; // Ensure list is not empty
        foreach (array_reverse($middlewareList) as $middleware) {
            $lastCallable = static function ($command) use ($middleware, $lastCallable) {
                return $middleware->execute($command, $lastCallable);
            };
        }
        return $lastCallable;
    }
}
