# Laravel Systems

Package combining a number of existing projects for easy installation and maintenance.

Including:

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
Add to require list:
```json
"nztim/laravel-systems": "^1.0"
```

### Configuration summary

##### Service Providers

```php
NZTim\Markdown\MarkdownServiceProvider::class,
```

##### Readme files 

- [nztim/markdown](/src/Markdown/readme.md) 


