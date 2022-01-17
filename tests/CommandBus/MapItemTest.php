<?php namespace NZTim\Tests\CommandBus;

use NZTim\CommandBus\Mapping\MapItem;
use PHPUnit\Framework\TestCase;

class MapItemTest extends TestCase
{
    /** @test */
    public function invoke_works()
    {
        $item = new MapItem('AddPostHandler');
        $this->assertEquals('AddPostHandler', $item->handlerClass());
        $this->assertEquals('handle', $item->handlerMethod());
    }

    /** @test */
    public function method_works()
    {
        $item = new MapItem('AddPostHandler', '__invoke');
        $this->assertEquals('AddPostHandler', $item->handlerClass());
        $this->assertEquals('__invoke', $item->handlerMethod());
    }
}
