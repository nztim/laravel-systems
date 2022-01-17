<?php

namespace NZTim\CommandBus\Mapping;

class MapItem
{
    private string $handlerClass;
    private string $handlerMethod;

    public function __construct(string $handlerClass, string $handlerMethod = 'handle')
    {
        $this->handlerClass = $handlerClass;
        $this->handlerMethod = $handlerMethod;
    }

    public function handlerClass(): string
    {
        return $this->handlerClass;
    }

    public function handlerMethod(): string
    {
        return $this->handlerMethod;
    }
}
