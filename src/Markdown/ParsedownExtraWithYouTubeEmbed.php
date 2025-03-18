<?php declare(strict_types=1);

namespace NZTim\Markdown;

use ParsedownExtra;

class ParsedownExtraWithYouTubeEmbed extends ParsedownExtra
{
    private bool $jotformEnabled = true;

    public function setJotformEnabled(bool $enabled)
    {
        $this->jotformEnabled = $enabled;
    }

    protected function inlineLink($Excerpt)
    {
        $inline = parent::inlineLink($Excerpt);
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
        // Make sure it's a link
        if (!$inline || $inline['element']['name'] !== 'a') {
            return $inline;
        }
        // Get the href
        $url = $inline['element']['attributes']['href'] ?? '';
        // Without a URL no need to process further
        if (!$url) {
            return $inline;
        }
        // YouTube handler
        $ytCode = $this->parseYouTube($url);
        if ($ytCode) {
            return $this->embedYouTube($inline, $ytCode);
        }
        // JotForm handler
        if ($this->jotformEnabled) {
            $jfCode = $this->parseJotForm($url);
            if ($jfCode) {
                return $this->embedJotForm($inline, $jfCode);
            }
        }
        // "target_blank" class handler
        $classes = explode(' ', $inline['element']['attributes']['class'] ?? '');
        if (in_array('target_blank', $classes)) {
            $inline['element']['attributes']['target'] = '_blank';
            $inline['element']['attributes']['rel'] = 'noopener';
            return $inline;
        }
        // Return unchanged
        return $inline;
    }

    private function parseYouTube(string $url): string
    {
        $pattern = '#^(?:https?://|//)?(?:www\.|m\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|watch\?.+&v=))([\w-]{11})(?![\w-])#';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? '';
    }

    public function embedYouTube(array $inline, string $code): array
    {
        $src = "https://www.youtube.com/embed/{$code}";
        $inline['element'] = [
            'name'       => 'div',
            'position'   => 1,
            'handler'    => 'element',
            'text'       => [
                'name'       => 'iframe',
                'text'       => '',
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

    private function parseJotForm($url): string
    {
        $matches = [];
        $pattern = '#^https://form\.jotform\.com/(\d*)#';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? '';
    }

    // <script type="text/javascript" src="https://form.jotform.com/jsform/221398418127055"></script>
    private function embedJotForm(array $inline, string $code): array
    {
        $src = "https://form.jotform.com/jsform/{$code}";
        $inline['element'] = [
            'name'     => 'div',
            'position' => 1,
            'handler'  => 'element',
            'text'     => [
                'name'       => 'script',
                'text'       => '',
                'attributes' => [
                    'type' => 'text/javascript',
                    'src'  => $src,
                ],
            ],
        ];
        return $inline;
    }
}

