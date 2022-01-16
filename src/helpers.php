<?php declare(strict_types=1);

use NZTim\Logger\Logger;
use NZTim\Markdown\ParsedownExtraWithYouTubeEmbed;

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


