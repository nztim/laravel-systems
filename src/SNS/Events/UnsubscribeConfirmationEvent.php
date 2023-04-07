<?php declare(strict_types=1);

namespace NZTim\SNS\Events;

class UnsubscribeConfirmationEvent implements SnsEventInterface
{
    public array $data;
    public string $arn;
    public string $message;

    private function __construct() {}

    public static function fromArray(array $data): UnsubscribeConfirmationEvent
    {
        $event = new UnsubscribeConfirmationEvent();
        $event->data = $data;
        $event->arn = $data['TopicArn'] ?? '';
        $event->message = $data['Message'] ?? '';
        return $event;
    }
}
