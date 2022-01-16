<?php declare(strict_types=1);

namespace NZTim\Markdown;

use ParsedownExtra;

class ParsedownExtraWithYouTubeEmbed extends ParsedownExtra
{
    protected function inlineLink($excerpt)
    {
        $inline = parent::inlineLink($excerpt);
        return $this->handleLink($inline);
    }

    // Do not embed bare URLs, only those with full link syntax
//    protected function inlineUrl($excerpt)
//    {
//        $inline = parent::inlineUrl($excerpt);
//        return $this->handleLink($inline);
//    }

    private function handleLink($inline)
    {
        if (!$inline) {
            return $inline;
        }
        $url = $inline['element']['attributes']['href'] ?? '';
        $code = $this->parseYouTube($url);
        if (!$code) {
            return $inline;
        }
        $src = "https://www.youtube.com/embed/{$code}";
        $inline['element'] = [
            'name'       => 'div',
            'position'   => 1,
            'handler'    => 'element',
            'text'       => [
                'name'    => 'iframe',
                'text' => '',
                'attributes' => [
                    'src'             => $src,
                    'frameborder'     => '0',
                    'allowfullscreen' => '1',
                ],
            ],
            'attributes' => [
                'class' => 'video embed-responsive embed-responsive-16by9',
            ],
        ];
        return $inline;
    }

    private function parseYouTube(string $url): string
    {
        $pattern = '#^(?:https?://|//)?(?:www\.|m\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|watch\?.+&v=))([\w-]{11})(?![\w-])#';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? '';
    }
}
