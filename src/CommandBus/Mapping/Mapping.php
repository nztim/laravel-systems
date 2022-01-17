<?php

namespace NZTim\CommandBus\Mapping;

class Mapping
{
    /** @var MapItem[] */
    private array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function map(string $commandClassName): ?MapItem
    {
        return $this->map[$commandClassName] ?? null;
    }
}
