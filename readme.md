# Laravel Systems

Package combining a number of existing projects for easy installation and maintenance.

Includes:

- **nztim/logger2:** Complements standard Laravel logging.
- **nztim/glide:** Glide image helper.
- **nztim/markdown:** Process markdown via function and Blade directives.

### Compatiblity

| Version | PHP  | Laravel |
| ------- | ---- | ------- |
| 1.0     | 8.0  | 8.0     |


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
NZTim\Logger\GlideServiceProvider::class,
NZTim\Logger\LoggerServiceProvider::class,
NZTim\Markdown\MarkdownServiceProvider::class,
```

##### Configuration files

```php
php artisan vendor:publish --provider=NZTim\Logger\LoggerServiceProvider

# filesystems.php
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

##### Readme files

- [nztim/glide](/src/Glide/readme.md)
- [nztim/logger2](/src/Logger/readme.md)
- [nztim/markdown](/src/Markdown/readme.md)
