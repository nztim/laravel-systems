<?php declare(strict_types=1);

namespace NZTim\MailLog\File;

class Content
{
    public string $html;
    public string $text;

    public function __construct(string $html, string $text)
    {
        $this->html = $html;
        $this->text = $text;
    }
}
