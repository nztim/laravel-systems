<?php declare(strict_types=1);

namespace NZTim\SES\Email;

use NZTim\Mailer\Mailer;

class SesUnsubscribeEmailHandler
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(SesUnsubscribeEmail $message)
    {
        $this->mailer->send($message);
    }
}
