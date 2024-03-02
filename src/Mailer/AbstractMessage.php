<?php declare(strict_types=1);

namespace NZTim\Mailer;

abstract class AbstractMessage
{
    public string $recipient;
    public string $subject;
    public string $view;
    public string|null $sender = null;
    public string|null $senderName = null;
    public string|null $replyTo = null;
    public string|null $recipientOverride = null;
    public string|array|null $cc = null;
    public string|array|null $bcc = null;
    public array $data = [];
    public string $messageId = ''; // Set automatically during sending process

    abstract public static function test(): AbstractMessage;

    abstract public function testLabel(): string;
}
