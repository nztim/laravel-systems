<?php declare(strict_types=1);

namespace NZTim\MailLog;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use NZTim\Mailer\MessageSent;

class MailLogEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MessageSent::class => [
            StoreMessageSent::class
        ],
    ];
}
