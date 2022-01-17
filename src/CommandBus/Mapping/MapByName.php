<?php

namespace NZTim\CommandBus\Mapping;

class MapByName
{
    public function map(string $commandClassName): MapItem
    {
        return new MapItem($commandClassName . 'Handler');
    }
}
