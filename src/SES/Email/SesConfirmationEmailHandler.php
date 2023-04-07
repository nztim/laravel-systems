<?php declare(strict_types=1);

namespace NZTim\SES\Email;

use NZTim\Mailer\Mailer;

class SesConfirmationEmailHandler
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(SesConfirmationEmail $message)
    {
        $this->mailer->send($message);
    }
}
