<?php declare(strict_types=1);

namespace NZTim\MailLog\Entry;

use Carbon\Carbon;
use NZTim\Mailer\MessageSent;
use RuntimeException;

class Entry
{
    private ?int $id;
    private string $type;
    private Carbon $date;
    private string $messageId;
    private string $recipient;
    private array $data;
    private Carbon $created;

    private function __construct()
    {
    }

    public static function fromMessageSent(MessageSent $messageSent, string $htmlPath, string $textPath): Entry
    {
        $entry = new Entry();
        $entry->id = null;
        $entry->type = Entry::TYPE_SENT;
        $entry->date = $messageSent->date();
        $entry->messageId = $messageSent->messageId();
        $entry->recipient = $messageSent->recipient();
        $entry->data = [
            'subject'    => $messageSent->subject(),
            'sender'     => $messageSent->sender(),
            'senderName' => $messageSent->senderName(),
            'replyTo'    => $messageSent->replyTo(),
            'cc'         => $messageSent->cc(),
            'bcc'        => $messageSent->bcc(),
            'htmlPath'   => $htmlPath,
            'textPath'   => $textPath,
        ];
        $entry->created = now();
        return $entry;
    }

    // Property Getters -----------------------------------------------------------------

    public function id(): ?int
    {
        return $this->id;
    }

    public function date(): Carbon
    {
        return $this->date;
    }

    public function recipient(): string
    {
        return $this->recipient;
    }

    public function subject(): string
    {
        if (!$this->hasContent()) {
            throw new RuntimeException('Cannot provide subject for Entry type: ' . $this->type);
        }
        return $this->data['subject'];
    }

    public function hasContent(): bool
    {
        return in_array($this->type, [Entry::TYPE_SENT, Entry::TYPE_DELIVERED]);
    }

    public function htmlFilePath(): string
    {
        if (!$this->hasContent()) {
            throw new RuntimeException('Cannot retrieve HTML file for Entry type: ' . $this->type);
        }
        return $this->data['htmlPath'];
    }

    public function textFilePath(): string
    {
        if (!$this->hasContent()) {
            throw new RuntimeException('Cannot retrieve HTML file for Entry type: ' . $this->type);
        }
        return $this->data['textPath'];
    }

    // Types ------------------------------------------------------------------

    public function type(): string
    {
        return $this->type;
    }

    public const TYPE_SENT = 'sent';
    public const TYPE_DELIVERED = 'delivered';
    public const TYPE_BLOCKED = 'blocked';
    public const TYPE_BOUNCE = 'bounce';
    public const TYPE_SPAM = 'spam';

    public function isSent(): bool
    {
        return $this->type === Entry::TYPE_SENT;
    }

    public function isDelivered(): bool
    {
        return $this->type === Entry::TYPE_DELIVERED;
    }

    public function isBlocked(): bool
    {
        return $this->type === Entry::TYPE_BLOCKED;
    }

    public function isBounce(): bool
    {
        return $this->type === Entry::TYPE_BOUNCE;
    }

    public function isSpam(): bool
    {
        return $this->type === Entry::TYPE_SPAM;
    }

    public static function typeSelect(string $first = null): array
    {
        $types = [
            Entry::TYPE_SENT      => 'Sent',
            Entry::TYPE_DELIVERED => 'Delivered',
            Entry::TYPE_BLOCKED   => 'Blocked',
            Entry::TYPE_BOUNCE    => 'Bounce',
            Entry::TYPE_SPAM      => 'Spam',
        ];
        return $first ? ['' => $first] + $types : $types;
    }

}
