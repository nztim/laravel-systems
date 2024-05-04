<?php

namespace NZTim\Html;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NZTim\Html\HtmlBuilder
 */
class HtmlFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'html';
    }
}
