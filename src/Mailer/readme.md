# Mailer for Laravel 5

### Installation

* Register the service provider: `NZTim\Mailer\MailerServiceProvider`

### Usage and architecture

* Create message command classes which extend `NZTim\Mailer\AbstractMessage`.
* Use the constructor and overrides as required to set up the required and optional data items.
* To send, pass the message (directly or via command bus) to `NZTim\Mailer\Mailer::send()`.
    * `DefaultMessageHandler` will take care of this for you, or write your own message handler which takes `Mailer` as a dependency.
* On successful send, an `NZTim\Mailer\MessageSent` event is dispatched which can be used to log or otherwise record outgoing messages.

### Testing

* Each message must implement a static `test()` method, which returns a new message with dummy data.
* Also required is a `testLabel()` method to give the message a name.
* Create a console command extending `NZTim\Mailer\TestEmailsCommand`
    * Override the `$recipient` property and add your default test recipient
    * Override the `tests()` method and return an array of your message classes
* The `php artisan testemails` can now be used to send test messages
    * The recipient will be overridden and cc/bcc instructions will be ignored
    * No `MessageSent` event will be fired for tests

### Updating

* Added to nztim/laravel-systems v1
* 4.0: The sender property of AbstractMessage is now optional (nullable), the default configured in `mail.php` will be used if no sender provided.
* 3.0: Requires PHP 7.4. AbstractMessage now has typed properties, and it's test() method can now return the subclass (covariance).
