<?php declare(strict_types=1);

namespace NZTim\Logger;

use Illuminate\Contracts\Mail\Mailer as LaravelMailer;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__.'/logger_config.php' => config_path('logger.php')]);
        if (config('logger.laravel', false)) {
            $this->app->get(\Illuminate\Events\Dispatcher::class)->listen(MessageLogged::class, LaravelLogListener::class);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/logger_config.php', 'logger');
        $this->app->bind(Logger::class, function () {
            $config = config('logger');
            $config['name'] = $config['name'] ?? config('app.name');
            $config['log_path'] = $config['log_path'] ?? storage_path('logs');
            if (config('app.debug')) {
                $config['email']['send'] = false;
            }
            return new Logger($config, app(LaravelCache::class), app(LaravelMailer::class));
        });
    }
}
