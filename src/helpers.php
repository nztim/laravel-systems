<?php declare(strict_types=1);

use NZTim\Logger\Logger;
use NZTim\Markdown\ParsedownExtraWithYouTubeEmbed;
use NZTim\SimpleHttp\Http;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

// LOGGER2 --------------------------------------------------------------------

if (!function_exists('log_info')) {
    function log_info(string $channel, string $message, array $context = []): void
    {
        app(Logger::class)->info($channel, $message, $context);
    }
}

if (!function_exists('log_warning')) {
    function log_warning(string $channel, string $message, array $context = []): void
    {
        app(Logger::class)->warning($channel, $message, $context);
    }
}

if (!function_exists('log_error')) {
    function log_error(string $channel, string $message, array $context = []): void
    {
        app(Logger::class)->error($channel, $message, $context);
    }
}

// MARKDOWN -------------------------------------------------------------------

function markdown(string $content): string
{
    /** @var ParsedownExtraWithYouTubeEmbed $converter */
    $converter = app('nztim-markdown-converter');
    return $converter->text($content);
}

// SYMFONY MAILER FACTORY -----------------------------------------------------

function getSymfonySmtpMailer(array $conf = null): Mailer
{
    if (is_null($conf)) {
        $conf = config('mail.mailers.' . config('mail.default'));
    }
    $dsn = sprintf('smtp://%s:%s@%s:%s', urlencode($conf['username']), urlencode($conf['password']), $conf['host'], $conf['port']);
    return new Mailer(Transport::fromDsn($dsn));
}

// GEOLOCATE ------------------------------------------------------------------

function geolocate(string $ip, int $timeout = 5): string
{
    // Skip unit tests
    if (app()->runningUnitTests()) {
        return 'NZ';
    }
    // Validate $ip
    $ip = trim($ip);
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return '??';
    }
    // Get result
    $domain = config('services.geolocate_domain', 'example.org');
    $url = "https://{$domain}/locate?ip={$ip}";
    $key = 'geo-' . md5($ip);
    if (cache()->has($key)) {
        return cache($key);
    }
    try {
        $response = (new Http())->timeout($timeout)->get($url);
    } catch (Throwable $e) {
        log_warning('comms', 'Geolocate helper failed: ' . $e->getMessage());
        return '??';
    }
    if (!$response->isOk()) {
        log_warning('comms', 'Geolocate helper failed, response code: ' . $response->status());
        return '??';
    }
    $result = str_limit(trim($response->body()), 2);
    cache()->put($key, $result, now()->addWeek());
    return $result;
}
