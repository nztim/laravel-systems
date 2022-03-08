# Laravel Systems

Package combining a number of existing projects for easy installation and maintenance.

### Compatiblity

| Version | PHP  | Laravel |
| ------- |------| ------- |
| 1.0     | ^8.0 | 8.0     |
| 2.0     | ^8.0 | 9.0     |

### Installation

Add to composer.json repositories array:

```json
{
    "type": "vcs",
    "url": "https://github.com/nztim/laravel-systems"
}
```

Then `composer require nztim/laravel-systems`

### Configuration summary

##### Service providers

```php
NZTim\CommandBus\Laravel\CommandBusServiceProvider::class,
NZTim\Helpers\HelpersServiceProvider::class,
NZTim\Logger\GlideServiceProvider::class,
NZTim\Logger\LoggerServiceProvider::class,
NZTim\Mailer\MailerServiceProvider::class,
NZTim\MailLog\MailLogServiceProvider::class,
NZTim\Markdown\MarkdownServiceProvider::class,
NZTim\ORM\Laravel\OrmServiceProvider::class,
NZTim\Queue\QueueServiceProvider::class,
```

##### Configuration

```php
php artisan vendor:publish --provider=NZTim\CommandBus\Laravel\CommandBusServiceProvider::class
php artisan vendor:publish --provider=NZTim\Logger\LoggerServiceProvider

php artisan qm:migration && php artisan maillog:migration && php artisan migrate # Add tables for queue and maillog

# commandbus.php
'middleware' => [
    NZTim\CommandBus\Laravel\DbTransactionMiddleware::class,
    NZTim\CommandBus\Laravel\LoggingMiddleware::class,
],

# filesystems.php
'emails' => [
    'driver' => 'local',
    'root'   => storage_path('app/emails'),
],
'glide_source' => [
    'driver' => 's3',
    'key'    => env('S3_ACCESS'),
    'secret' => env('S3_SECRET'),
    'region' => env('S3_REGION', 'ap-southeast-2'),
    'bucket' => env('S3_BUCKET'),
],
'glide_cache' => [
    'driver' => 'local',
    'root'   => storage_path('app/cache'),
],
```

##### .env values

```php
# GLIDE
S3_ACCESS=
S3_SECRET=
S3_REGION=
S3_BUCKET=

# LOGGER
LOGGER_EMAIL_SENDER=app@example.org
LOGGER_EMAIL_RECIPIENT=dev@example.org
```

##### Scheduler entries

```php 
$schedule->command('qm:daemon 50')     ->everyMinute();
$schedule->command('qm:logstatus')     ->dailyAt('4:00');
$schedule->command('maillog:prune')    ->dailyAt('4:02');
```

##### Readme files

- [nztim/commandbus](/src/CommandBus/readme.md)
- [nztim/glide](/src/Glide/readme.md)
- [nztim/helpers](/src/Helpers/readme.md)
- [nztim/input](/src/Input/readme.md)
- [nztim/logger2](/src/Logger/readme.md)
- [nztim/mailer2](/src/Mailer/readme.md)
- [nztim/maillog](/src/MailLog/readme.md)
- [nztim/markdown](/src/Markdown/readme.md)
- [nztim/orm](/src/Orm/readme.md)
- [nztim/queue](/src/Queue/readme.md)

##### Upgrading

* **2.0:** No API changes, SMTP mail config in newer format is now required for Logger. `form()` and `schema()` helpers are now in this package so can be removed from the application.
