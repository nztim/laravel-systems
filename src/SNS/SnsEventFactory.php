<?php declare(strict_types=1);

namespace NZTim\SNS;

use NZTim\SNS\Events\NotificationEvent;
use NZTim\SNS\Events\SnsEventInterface;
use NZTim\SNS\Events\SubscriptionConfirmationEvent;
use NZTim\SNS\Events\UnsubscribeConfirmationEvent;
use RuntimeException;

class SnsEventFactory
{
    private SnsMessageValidator $validator;

    public function __construct(SnsMessageValidator $validator)
    {
        $this->validator = $validator;
    }

    public function process(array $data): ?SnsEventInterface
    {
        $result = $this->validator->validate($data);
        if (!$result->success) {
            log_warning('sns', 'Validation failure: ' . $result->message, $data);
            return null;
        }
        $type = $data['Type'] ?? '';
        return match ($type) {
            'SubscriptionConfirmation' => SubscriptionConfirmationEvent::fromArray($data),
            'Notification' => NotificationEvent::fromArray($data),
            'UnsubscribeConfirmation' => UnsubscribeConfirmationEvent::fromArray($data),
            default => throw new RuntimeException('Unknown SNS Message Type: ' . $type),
        };
    }
}

/*
* https://docs.aws.amazon.com/sns/latest/dg/sns-message-and-json-formats.html
*/
