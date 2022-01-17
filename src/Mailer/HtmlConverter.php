<?php declare(strict_types=1);

namespace NZTim\Mailer;

use League\HTMLToMarkdown\HtmlConverter as LeagueConverter;

class HtmlConverter
{
    private LeagueConverter $converter;

    public function __construct(LeagueConverter $converter)
    {
        $this->converter = $converter;
        $this->converter->setOptions([
            'strip_tags'    => true,
            'use_autolinks' => true,
            'remove_nodes'  => 'head',
        ]);
    }

    public function convert(string $html): string
    {
        return $this->converter->convert($html);
    }
}
