<?php declare(strict_types=1);

namespace NZTim\Mailer;

abstract class AbstractMessage
{
    public string $recipient;
    public string $subject;
    public string $view;
    public ?string $sender = null;
    public ?string $senderName = null;
    public ?string $replyTo = null;
    public ?string $recipientOverride = null;
    public ?string $cc = null;
    public ?string $bcc = null;
    public array $data = [];
    public string $messageId = ''; // Set automatically during sending process

    abstract public static function test(): AbstractMessage;

    abstract public function testLabel(): string;
}
