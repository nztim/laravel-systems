# Laravel Systems

Package combining a number of existing projects for easy installation and maintenance.

Includes:

- **nztim/logger2:** Complements standard Laravel logging.
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
NZTim\Logger\LoggerServiceProvider::class,
NZTim\Markdown\MarkdownServiceProvider::class,
```

##### Configuration files

```php
php artisan vendor:publish --provider=NZTim\Logger\LoggerServiceProvider
```

##### .env values

```php
# LOGGER
LOGGER_EMAIL_SENDER=app@example.org
LOGGER_EMAIL_RECIPIENT=dev@example.org
```

##### Readme files

- [nztim/logger2](/src/Logger/readme.md)
- [nztim/markdown](/src/Markdown/readme.md)
