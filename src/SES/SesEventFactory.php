<?php declare(strict_types=1);

namespace NZTim\SES;

use NZTim\Queue\QueueManager;
use NZTim\SES\Email\SesConfirmationEmail;
use NZTim\SES\Email\SesUnsubscribeEmail;
use NZTim\SES\Events\SesBounce;
use NZTim\SES\Events\SesComplaint;
use NZTim\SES\Events\SesDelivery;
use NZTim\SES\Events\SesEvent;
use NZTim\SNS\Events\NotificationEvent;
use NZTim\SNS\Events\SnsEventInterface;
use NZTim\SNS\Events\SubscriptionConfirmationEvent;
use NZTim\SNS\Events\UnsubscribeConfirmationEvent;
use RuntimeException;

class SesEventFactory
{
    private SesConfig $config;
    private QueueManager $qm;

    public function __construct(SesConfig $config, QueueManager $qm)
    {
        $this->config = $config;
        $this->qm = $qm;
    }

    public function process(SnsEventInterface $snsEvent): ?SesEvent
    {
        if (!$this->config->filterArn($snsEvent->arn)) {
            return null;
        }
        return match (get_class($snsEvent)) {
            SubscriptionConfirmationEvent::class => $this->subscription($snsEvent),
            UnsubscribeConfirmationEvent::class => $this->unsub($snsEvent),
            NotificationEvent::class => $this->notification($snsEvent),
            default => throw new RuntimeException("Unknown SNS Event type: " . get_class($snsEvent)),
        };
    }

    private function subscription(SubscriptionConfirmationEvent $sns): ?SesEvent
    {
        log_info('sns', 'Subscription confirmation: ' . $sns->message, $sns->data);
        if ($this->config->snsSubsRecipient()) {
            $this->qm->add(new SesConfirmationEmail($this->config->snsSubsRecipient(), $sns->message, $sns->url, $sns->data));
        }
        return null;
    }

    private function unsub(UnsubscribeConfirmationEvent $sns): ?SesEvent
    {
        log_info('sns', 'Unsubscribe confirmation: ' . $sns->message, $sns->data);
        if ($this->config->snsSubsRecipient()) {
            $this->qm->add(new SesUnsubscribeEmail($this->config->snsSubsRecipient(), $sns->message, $sns->data));
        }
        return null;
    }

    private function notification(NotificationEvent $sns): ?SesEvent
    {
        log_info('ses', $sns->message);
        $data = json_decode($sns->message, true);
        if (!is_array($data)) {
            throw new \RuntimeException("Unable to decode json from message: " . $sns->message);
        }
        return match (array_get($data, 'notificationType', '')) {
            'Bounce' => new SesBounce($data),
            'Complaint' => new SesComplaint($data),
            'Delivery' => new SesDelivery($data),
            default => throw new RuntimeException('Invalid notificationType in SES data: ' . array_get($data, 'notificationType', '')),
        };
    }
}

/*
 * Top-level object contains: notificationType, mail, [bounce|complaint|delivery]
 * This page has all permutations: https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html
 */
