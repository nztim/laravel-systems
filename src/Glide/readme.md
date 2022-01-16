# Glide Helper

### Installation & Configuration:

* Add `GlideServiceProvider::class` to `app.php`
* Add filesystem configuration disk entries for `glide_source` and `glide_cache`, for example:
```php
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

* Add route named `image.serve`, extend supplied abstract controller `ImageController`
* Override methods in ImageController as required to choose permitted parameters and handle errors
* Use Laravel signed routes for protection from mass resize attacks
* Then add the images to your views:

```php
<img src="{{ $glideImage->url('500.auto.max') }}">
<img src="{{ $glideImage->url(['w' => 500, 'h' => 'auto', 'fit' => 'max']) }}">
<img src="{{ route('image.serve', [$filename, 'w' => 500, 'h' => 'auto', 'fit' => 'max']) }}">
```

* Files are stored as md5hash.extension, using 2 folder levels. E.g. 12/34/123456.jpg
* Extensions are lowercase and `jpeg` is changed to `jpg`

### Upgrade

* Added to nztim/laravel-systems v1.0
* v2.0: Update all image URLs and parameter usage to new simplified format 
