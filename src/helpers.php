<?php declare(strict_types=1);

function markdown(string $content): string
{
    /** @var \NZTim\Markdown\ParsedownExtraWithYouTubeEmbed $converter */
    $converter = app('nztim-markdown-converter');
    return $converter->text($content);
}

