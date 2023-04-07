# SNS

Package to receive, verify and process SNS webhooks into Events.

### Usage

* Create a route to receive SNS requests, connect it to `NZTim\SNS\WebhookController` (or your own handler).
* Configure your EventServiceProvider to handle SNS events as required: `SubscriptionConfirmationEvent`, `UnsubscribeConfirmationEvent` and `NotificationEvent`.

### Upgrading

* 6.0: PHP 8.1, laravel-systems 3.9 (Laravel 10) and major revision. Event objects now use public properties instead of accessor methods.
* 5.0: Requires PHP8 and nztim/laravel-systems 2.0 (Laravel 9).
* 2.0: Moved WebhookController, removed example SnsEventServiceProvider and SnsLoggingListener - make sure they are not referenced.

