<?php declare(strict_types=1);

namespace NZTim\SNS;

use NZTim\SNS\Events\SnsEventInterface;

class SnsWebhookCustomHandler
{
    private SnsEventFactory $eventFactory;

    public function __construct(SnsEventFactory $eventFactory)
    {
        $this->eventFactory = $eventFactory;
    }

    public function handle(SnsWebhookCustom $webhook): ?SnsEventInterface
    {
        return $this->eventFactory->process($webhook->data);
    }
}
