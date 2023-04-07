<?php

namespace NZTim\Tests\CommandBus;

use LogicException;
use NZTim\CommandBus\Mapping\MapByName;
use NZTim\CommandBus\Mapping\MapItem;
use NZTim\CommandBus\Mapping\Mapping;
use NZTim\CommandBus\Middleware\CommandHandlerMiddleware;
use NZTim\Tests\CommandBus\Fixtures\InvokeCommand;
use NZTim\Tests\CommandBus\Fixtures\InvokeCommandHandler;
use NZTim\Tests\CommandBus\Fixtures\DefaultCommand;
use NZTim\Tests\CommandBus\Fixtures\DefaultCommandHandler;
use NZTim\Tests\CommandBus\Fixtures\MissingDefaultCommand;
use NZTim\Tests\CommandBus\Fixtures\MissingDefaultCommandHandler;
use NZTim\Tests\CommandBus\Fixtures\MissingMethodCommand;
use NZTim\Tests\CommandBus\Fixtures\MissingMethodCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use RuntimeException;

class CommandHandlerMiddlewareTest extends TestCase
{
    /** @var CommandHandlerMiddleware */
    private $middleware;
    /** @var ContainerInterface&MockObject */
    private $container;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $mapping = new Mapping([
            InvokeCommand::class        => new MapItem(InvokeCommandHandler::class, '__invoke'),
            MissingMethodCommand::class => new MapItem(MissingMethodCommandHandler::class, '__invoke'),
        ]);
        $this->middleware = new CommandHandlerMiddleware($this->container, $mapping, new MapByName());
    }

    /** @test */
    public function default_handling_works()
    {
        $this->container->expects($this->once())
            ->method('get')
            ->with(DefaultCommandHandler::class)
            ->willReturn(new DefaultCommandHandler());
        $command = new DefaultCommand();
        $this->assertEquals($command->val(), $this->middleware->execute($command, $this->mockNext()));
    }

    /** @test */
    public function mapped_handling_works()
    {
        $this->container->expects($this->once())
            ->method('get')
            ->with(InvokeCommandHandler::class)
            ->willReturn(new InvokeCommandHandler());
        $command = new InvokeCommand();
        $this->assertEquals($command->val(), $this->middleware->execute($command, $this->mockNext()));
    }

    /** @test */
    public function missing_method()
    {
        $this->expectException(RuntimeException::class);
        $this->container->expects($this->once())
            ->method('get')
            ->with(MissingMethodCommandHandler::class)
            ->willReturn(new MissingMethodCommandHandler());
        $command = new MissingMethodCommand();
        $this->middleware->execute($command, $this->mockNext());
    }

    /** @test */
    public function missing_default()
    {
        $this->expectException(RuntimeException::class);
        $this->container->expects($this->once())
            ->method('get')
            ->with(MissingDefaultCommandHandler::class)
            ->willReturn(new MissingDefaultCommandHandler());
        $command = new MissingDefaultCommand();
        $this->middleware->execute($command, $this->mockNext());
    }

    protected function mockNext(): callable
    {
        return static function (): void {
            throw new LogicException('Middleware fell through to next callable, this should not happen in the test.');
        };
    }
}
