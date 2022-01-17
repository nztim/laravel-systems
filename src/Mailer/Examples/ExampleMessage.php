<?php

namespace NZTim\Mailer\Examples;

use NZTim\Mailer\AbstractMessage;

class ExampleMessage extends AbstractMessage
{
    public ?string $sender;
    public string $recipient;
    public string $subject;
    public string $view;
    public array $data = [];

    public function __construct(string $name, string $email, string $message)
    {
        $this->data = [
            'name'    => $name,
            'email'   => $email,
            'message' => $message,
        ];
        $this->sender = config('mail.address.system');
        $this->recipient = config('mail.address.admin');
        $this->subject = 'Contact form submission received from ' . $name;
        $this->view = 'emails.contact';
    }

    public static function test(): ExampleMessage
    {
        return new ExampleMessage('Barry White', 'barry@example.org', 'This is a message');
    }

    public function testLabel(): string
    {
        return 'Contact form';
    }
}
