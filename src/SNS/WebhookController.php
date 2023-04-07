<?php declare(strict_types=1);

namespace NZTim\SNS;

use NZTim\Queue\QueueManager;

class WebhookController
{
    private QueueManager $qm;

    public function __construct(QueueManager $qm)
    {
        $this->qm = $qm;
    }

    public function handle()
    {
        $body = request()->getContent();
        if (!is_array(json_decode($body, true))) {
            log_info('sns', 'Unable to decode message: ' . $body);
            return response()->json(['result' => 'Unable to process'], 400);
        }
        $this->qm->add(new SnsWebhook($body));
        return response()->json(['result' => 'OK']);
    }
}
