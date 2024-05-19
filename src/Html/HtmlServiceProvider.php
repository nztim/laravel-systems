<?php

namespace NZTim\Html;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Store;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\View\Factory;
use Illuminate\Support\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton(FormBuilder::class, function ($app) {
            return new FormBuilder($app->make(UrlGenerator::class), $app->make(Factory::class), $app->make(Store::class));
        });
    }

    public function provides()
    {
        return [FormBuilder::class];
    }
}
