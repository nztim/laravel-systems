# Laravel 5 Helpers

* Register the service provider: `NZTim\Helpers\HelpersServiceProvider`

### Helper functions
* `autolink(string $content)` - autolink a string
* `flash($message, $type = 'danger')` - send flash messages to the view
* `markdown(string $content)` - renders Markdown GFM style including line breaks
* `sanitize(mixed $data)` - runs htmlspecialchars() and trim() on a string or an array of strings
* `cached_asset($path)` - returns asset path with hashed integer value, e.g. `main.css` -> `main.847389233.css`
    * Note: .htaccess rule is required, see below
* `form()` - replacement for `Form::` facade 
* `schema()` - replacement for `Schema::` facade

### Blade directives
* `@autolink($string)` - sanitizes the string, autolinks and runs nl2br
* `@formerror` - echoes the error message with a Bootstrap-compatible red background
* `@markdown` - Renders markdown, HTML-escapes the content (using Parsedown)
* `@nl2br($string)` - sanitizes the string and then runs nl2br
* `@pagination($paginator)` - calls `render()`

### Validator extensions
* `commonpwd` - ensures a password is not on a list of 10,000 common passwords
* `fileext:jpg,jpeg,png,gif` - validates file extension
* `after_or_equal:2019-11-01` - date is after or equal date provided, uses strtotime for comparison
* `utf8` - validates UTF-8 string

### .htaccess for `cached_asset()`
Add this before the Laravel rewrite rule
```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.+)\.(\d+)\.(bmp|css|cur|gif|ico|jpe?g|js|png|svgz?|webp|webmanifest)$ $1.$3 [L]
</IfModule>
```

### Envcheck command
Add `php artisan envcheck` to your deploy process to enable a comparison check between `.env` and `.env.example`

### ServerCheck command
Store copies of your server configuration files (e.g. FPM pool config) in `resources/server-conf`.
Add `resources/server-conf/files.php`, which returns an array of filenames and server paths. 
Run `php artisan server-conf-check` to compare the project files and the files on the server.
Add the command to your deploy process to make sure the server is configured in accordance with the project records.

### Local configuration (.env replacement)
To use this functionality add this method override to your HTTP and Console Kernel classes:

```
protected function bootstrappers()
{
    return array_merge([\NZTim\Helpers\Local\Local::class], $this->bootstrappers);
}
```

Replace your `.env[.example]` file with `local[.dist].php`, which uses normal config file format.  
Add `local.php` to your `.gitignore`.  
Now you can use local() in your config files in the same way that you use env() but without the downsides.

### Updating

* Added to nztim/laravel-systems v1
* 3.0: Removed markdown handling, replace by installing nztim/markdown
