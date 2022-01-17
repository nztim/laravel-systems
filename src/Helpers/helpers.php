<?php

function flash($message, $type = 'danger')
{
    session()->flash('flash_message', $message);
    session()->flash('flash_type', $type);
}

function sanitize($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
        return $data;
    }
    return htmlspecialchars(trim($data), ENT_HTML5, 'UTF-8', false);
}

// Matches http(s)://anything.anything until whitespace and links it
function autolink(string $content): string
{
    return preg_replace('@https?:\/\/\S*\.\S*@i', '<a target="_blank" rel="nofollow" href="\\0">\\0</a>', $content);
}

function active(string $uri)
{
    return request()->is($uri) ? 'active' : '';
}

function excerpt(string $content, int $max = 150, string $append = '…'): string
{
    $excerpt = str_replace(["\r", "\n"], " ", $content);
    if (strlen($excerpt) > $max) {
        $excerpt = substr($excerpt, 0, $max);
        $cutoff = strrpos($excerpt, ' ');
        $excerpt = substr($excerpt, 0, $cutoff);
        $excerpt .= $append;
    }
    return $excerpt;
}

function countrySelect(): array
{
    return include(__DIR__ . DIRECTORY_SEPARATOR . 'countries.php');
}

/* https://github.com/h5bp/server-configs-apache/blob/master/src/web_performance/filename-based_cache_busting.conf
 * Requires htaccess rule:
 * <IfModule mod_rewrite.c>
 *     RewriteEngine On
 *     RewriteCond %{REQUEST_FILENAME} !-f
 *     RewriteRule ^(.+)\.(\d+)\.(bmp|css|cur|gif|ico|jpe?g|js|png|svgz?|webp|webmanifest)$ $1.$3 [L]
 * </IfModule>
 */
function cached_asset(string $asset): string
{
    $realPath = public_path($asset);
    if (!file_exists($realPath)) {
        throw new InvalidArgumentException('File not found at ' . $realPath);
    }
    $hash = sprintf("%u", crc32(md5_file($realPath)));
    $extension = pathinfo($realPath, PATHINFO_EXTENSION);
    $stripped = substr($asset, 0, -(strlen($extension) + 1));
    $path = implode('.', [$stripped, $hash, $extension]);
    return cdn($path);
}

// Based on https://www.keycdn.com/support/laravel-cdn-integration/
function cdn(string $asset): string
{
    $cdnDomain = config('services.cdn.domain');
    if (app()->environment() != 'production' || !$cdnDomain) {
        return asset($asset);
    }
    // Remove query string
    $asset = explode("?", $asset);
    $asset = $asset[0];
    // "//<cdnDomain>/path/to/asset.jpg"
    return "//" . rtrim($cdnDomain, "/") . "/" . ltrim($asset, "/");
}

function route_force_host($name, $parameters = [])
{
    $base = rtrim(config('app.url'), '/');
    return url($base . route($name, $parameters, false));
}

/**
 *  Check if input string is a valid YouTube URL
 *  and try to extract the YouTube Video ID from it.
 * @param   $url   string   The string that shall be checked.
 * @return  mixed           Returns YouTube Video ID, or (boolean) false.
 * @author  Stephan Schmitz <eyecatchup@gmail.com>
 */
function parse_yturl($url): string
{
    $pattern = '#^(?:https?://|//)?(?:www\.|m\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|watch\?.+&v=))([\w-]{11})(?![\w-])#';
    preg_match($pattern, $url, $matches);
    return $matches[1] ?? '';
}

function requestInfo(): array
{
    $info = [];
    $info['ip'] = request()->getClientIp();
    $info['method'] = request()->server('REQUEST_METHOD');
    $info['url'] = request()->url();
    $info['auth'] = auth()->check();
    $info['authid'] = auth()->id();
    $input = request()->all();
    foreach (['password', 'password_confirmation', '_token'] as $item) {
        if (isset($input[$item])) {
            unset($input[$item]);
        }
    }
    $info['input'] = $input;
    return $info;
}
