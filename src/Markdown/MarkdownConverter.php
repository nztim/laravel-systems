<?php declare(strict_types=1);

namespace NZTim\Markdown;

interface MarkdownConverter
{
    public function convert(string $markdown): string;
}
