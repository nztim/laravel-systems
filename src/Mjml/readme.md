# MJML 

Package to compile MJML into HTML using the MJML API.

### Configuration

* Add config to `services.php`:
```
'mjml'          => [
    'url'    => env('MJML_URL', 'https://api.mjml.io/v1/render'),
    'app_id' => env('MJML_APP_ID', ''),
    'secret' => env('MJML_SECRET', ''),
],
```
* Add associated .env values:
```
# MJML API
MJML_URL=https://api.mjml.io/v1/render
MJML_APP_ID=
MJML_SECRET=
```
* Register the service provider MjmlServiceProvider::class

### Usage

`php artisan mjml:compile path/to/file.mjml` will compile the mjml and write it to `path/to/file.blade.php`


