<?php declare(strict_types=1);

namespace NZTim\SES;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use NZTim\SNS\Events\NotificationEvent;
use NZTim\SNS\Events\SubscriptionConfirmationEvent;
use NZTim\SNS\Events\UnsubscribeConfirmationEvent;

class SesEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SubscriptionConfirmationEvent::class => [
            SnsEventListener::class,
        ],
        UnsubscribeConfirmationEvent::class  => [
            SnsEventListener::class,
        ],
        NotificationEvent::class             => [
            SnsEventListener::class,
        ],
    ];
}
