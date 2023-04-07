<?php declare(strict_types=1);

namespace NZTim\SES;

use Illuminate\Events\Dispatcher;
use NZTim\SNS\Events\SnsEventInterface;

class SnsEventListener
{
    private SesEventFactory $eventFactory;
    private Dispatcher $dispatcher;

    public function __construct(SesEventFactory $eventFactory, Dispatcher $dispatcher)
    {
        $this->eventFactory = $eventFactory;
        $this->dispatcher = $dispatcher;
    }

    public function handle(SnsEventInterface $snsEvent)
    {
        $sesEvent = $this->eventFactory->process($snsEvent);
        if ($sesEvent) {
            $this->dispatcher->dispatch($sesEvent);
        }
    }
}
