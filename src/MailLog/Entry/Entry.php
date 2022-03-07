<?php declare(strict_types=1);

namespace NZTim\MailLog\Entry;

use Carbon\Carbon;
use NZTim\Mailer\MessageSent;

class Entry
{
    private ?int $id;
    private string $type;
    private Carbon $date;
    private string $messageId;
    private string $recipient;
    private array $data;
    private Carbon $created;

    public const TYPE_SENT = 'sent';

    private function __construct()
    {
    }

    public static function createFromMessageSent(MessageSent $messageSent): Entry
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
        ];
        $entry->created = now();
        return $entry;
    }

    // Property Getters -----------------------------------------------------------------

    public function id(): ?int
    {
        return $this->id;
    }
}
