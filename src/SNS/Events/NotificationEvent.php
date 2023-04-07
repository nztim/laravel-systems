<?php declare(strict_types=1);

namespace NZTim\SNS\Events;

class NotificationEvent implements SnsEventInterface
{
    public array $data;
    public string $arn;
    public string $message;

    private function __construct() {}

    public static function fromArray(array $data): NotificationEvent
    {
        $event = new NotificationEvent();
        $event->data = $data;
        $event->arn = $data['TopicArn'] ?? '';
        $event->message = $data['Message'] ?? '';
        return $event;
    }
}
