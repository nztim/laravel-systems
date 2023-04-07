<?php declare(strict_types=1);

namespace NZTim\SES\Events;

enum BounceClassification
{
    case Bounce;
    case NoEmail;
    case Rejected;
    case SoftFail;
    case Suppressed;
}
