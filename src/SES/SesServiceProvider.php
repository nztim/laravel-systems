<?php declare(strict_types=1);

namespace NZTim\SES;

use Illuminate\Support\ServiceProvider;

class SesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/ses.php', 'ses');
        $this->app->register(SesEventServiceProvider::class);
    }

    public function boot()
    {
        $dir = __DIR__;
        $this->loadViewsFrom("{$dir}/views", 'nztses');
        $this->publishes([
            "{$dir}/views"          => resource_path('views/vendor/nztses'),
            "{$dir}/config/ses.php" => config_path('ses.php'),
        ]);
    }
}
