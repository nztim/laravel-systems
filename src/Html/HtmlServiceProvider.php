<?php

namespace NZTim\Html;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton(FormBuilder::class, function ($app) {
            return new FormBuilder($app['url'], $app['view'], $app['session.store'], $app['request']);
        });
    }

    public function provides()
    {
        return [FormBuilder::class];
    }
}
