<?php

namespace NZTim\Mailer\Examples;

use NZTim\Mailer\Mailer;

class ExampleMessageHandler
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(ExampleMessage $message)
    {
        // If you need to reconstitute models from IDs - do that here
        // If you only need to send the message then you can use the DefaultMessageHandler
        $this->mailer->send($message);
    }
}
