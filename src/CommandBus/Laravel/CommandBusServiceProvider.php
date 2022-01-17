<?php

namespace NZTim\CommandBus\Laravel;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use NZTim\CommandBus\CommandBus;
use NZTim\CommandBus\Mapping\MapItem;
use NZTim\CommandBus\Mapping\Mapping;
use NZTim\CommandBus\Middleware\CommandHandlerMiddleware;

class CommandBusServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config.php', 'commandbus');
        $this->app->bind(Mapping::class, function () {
            $map = [];
            foreach (config('commandbus.map', []) as $command => $handler) {
                $map[$command] = is_array($handler) ? new MapItem($handler[0], $handler[1]) : new MapItem($handler);
            }
            return new Mapping($map);
        });
        $this->app->singleton(CommandBus::class, function (Application $app) {
            $middleware = [];
            foreach (config('commandbus.middleware', []) as $class) {
                $middleware[] = $app->make($class);
            }
            $middleware[] = $app->make(CommandHandlerMiddleware::class);
            return new CommandBus(...$middleware);
        });
    }

    public function boot()
    {
        $this->publishes([__DIR__.'/config.php' => config_path('commandbus.php')]);
    }
}
