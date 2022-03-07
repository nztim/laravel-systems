<?php declare(strict_types=1);

namespace NZTim\MailLog;

use NZTim\Mailer\MessageSent;

class StoreMessageSent
{
    private MailLogCrud $crud;

    public function __construct(MailLogCrud $crud)
    {
        $this->crud = $crud;
    }

    public function handle(MessageSent $messageSent)
    {
        $this->crud->createFromMessageSent($messageSent);
    }
}
