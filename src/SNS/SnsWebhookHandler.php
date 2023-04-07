<?php declare(strict_types=1);

namespace NZTim\SNS;

use Illuminate\Events\Dispatcher;

class SnsWebhookHandler
{
    private SnsEventFactory $eventFactory;
    private Dispatcher $dispatcher;

    public function __construct(SnsEventFactory $eventFactory, Dispatcher $dispatcher)
    {
        $this->eventFactory = $eventFactory;
        $this->dispatcher = $dispatcher;
    }

    public function handle(SnsWebhook $webhook)
    {
        $result = $this->eventFactory->process($webhook->data);
        if (!is_null($result)) {
            $this->dispatcher->dispatch($result);
        }
    }
}
