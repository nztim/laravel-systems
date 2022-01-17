<?php

namespace NZTim\Mailer;

use Illuminate\Support\ServiceProvider;

class MailerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'nztmailer');
    }
}
