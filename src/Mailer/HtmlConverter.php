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
            'remove_nodes'  => 'head',
            'strip_tags'    => true,
            'use_autolinks' => true,
        ]);
    }

    public function convert(string $html): string
    {
        return $this->converter->convert($html);
    }
}

/*
    LeagueConverter as of 5.0 can throw InvalidArgumentException|RuntimeException if transforming malformed HTML.
    For now, leave it in place as mail should be queued, and you want to know if there is a problem.
    But potentially you could log errors and do something else as a backup, such as converting with suppression enabled.
*/
