<?php

namespace NZTim\Mailer;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class CssInliner
{
    private CssToInlineStyles $csstoinline;

    public function __construct(CssToInlineStyles $csstoinline)
    {
        $this->csstoinline = $csstoinline;
    }

    public function process(string $html): string
    {
        return $this->csstoinline->convert($html);
    }
}
