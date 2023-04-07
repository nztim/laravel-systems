<?php

namespace NZTim\Tests\CommandBus;

use NZTim\CommandBus\Mapping\MapItem;
use NZTim\CommandBus\Mapping\Mapping;
use NZTim\Tests\TestCase;

class MappingTest extends TestCase
{
    /** @test */
    public function works_ok()
    {
        $map = [
            'Command1' => new MapItem('Command1Handler'),
            'Command2' => new MapItem('Command2Handler'),
        ];
        $mapping = new Mapping($map);
        $this->assertEquals('Command1Handler', $mapping->map('Command1')->handlerClass());
        $this->assertEquals('Command2Handler', $mapping->map('Command2')->handlerClass());
        $this->assertNull($mapping->map('Command3'));
    }
}
