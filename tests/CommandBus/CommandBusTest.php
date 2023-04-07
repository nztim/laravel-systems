<?php

namespace NZTim\Tests\CommandBus;

use NZTim\CommandBus\CommandBus;
use NZTim\CommandBus\Middleware;
use NZTim\Tests\CommandBus\Fixtures\AddTaskCommand;
use NZTim\Tests\TestCase;

class CommandBusTest extends TestCase
{
    /** @test */
    public function no_middleware_executes_without_error()
    {
        (new CommandBus())->handle(new AddTaskCommand());
        $this->assertTrue(true);
    }

    /** @test */
    public function single_middleware_works(): void
    {
        $middleware = $this->createMock(Middleware::class);
        $middleware->expects(self::once())->method('execute')->willReturn('foobar');
        $commandBus = new CommandBus($middleware);
        $this->assertEquals('foobar', $commandBus->handle(new AddTaskCommand()));
    }

    /** @test */
    public function multiple_middleware_works_in_correct_order(): void
    {
        $executionOrder = [];

        $middleware1 = $this->createMock(Middleware::class);
        $middleware1->method('execute')->willReturnCallback(
            static function ($command, $next) use (&$executionOrder) {
                $executionOrder[] = 1;
                return $next($command);
            }
        );

        $middleware2 = $this->createMock(Middleware::class);
        $middleware2->method('execute')->willReturnCallback(
            static function ($command, $next) use (&$executionOrder) {
                $executionOrder[] = 2;
                return $next($command);
            }
        );

        $middleware3 = $this->createMock(Middleware::class);
        $middleware3->method('execute')->willReturnCallback(
            static function () use (&$executionOrder) {
                $executionOrder[] = 3;
                return 'foobar';
            }
        );

        $commandBus = new CommandBus($middleware1, $middleware2, $middleware3);
        $this->assertEquals('foobar', $commandBus->handle(new AddTaskCommand()));
        $this->assertEquals([1, 2, 3], $executionOrder);
    }
}
