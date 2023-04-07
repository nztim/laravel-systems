<?php declare(strict_types=1);

namespace NZTim\SES\Events;

interface SesEvent
{
    public function sesMail(): SesMailDetails;
}
