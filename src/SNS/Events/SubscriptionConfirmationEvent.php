<?php declare(strict_types=1);

namespace NZTim\SNS\Events;

class SubscriptionConfirmationEvent implements SnsEventInterface
{
    public array $data;
    public string $arn;
    public string $message;
    public string $url;

    private function __construct() {}

    public static function fromArray(array $data): SubscriptionConfirmationEvent
    {
        $event = new SubscriptionConfirmationEvent();
        $event->data = $data;
        $event->arn = $data['TopicArn'] ?? '';
        $event->message = $data['Message'] ?? '';
        $event->url = $data['SubscribeURL'] ?? '';
        return $event;
    }
}
