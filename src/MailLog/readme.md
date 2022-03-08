# Mail Log

### Configuration

* Add Service Provider `NZTim\MailLog\MailLogServiceProvider::class,`
* Create and run migration `php artisan maillog:migration && php artisan migrate`
* Add scheduler entry to console Kernel.php: `$schedule->command('maillog:prune')->dailyAt('4:00');`
* Set up disk for `emails` files in `config/filesystems.php`:
```php
'emails' => [
    'driver' => 'local',
    'root'   => storage_path('app/emails'),
],
```

### TODO

* Listens to MessageSent and stores entries in db and filesystem. Also deletes old entries after 2 weeks.
* Next is a generic controller to display paginated list of entries, and generic show page for content.
* Generic views to be copied and inserted into a theme.
* Then schedule auto-prune of old entries/content
* Then set up route for handling SNS->SES reports and including those as well, copy it all from C2
