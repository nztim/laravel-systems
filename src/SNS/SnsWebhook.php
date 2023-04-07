<?php declare(strict_types=1);

namespace NZTim\SNS;

class SnsWebhook
{
    public array $data;

    public function __construct(string $body)
    {
        $this->data = json_decode($body, true);
    }
}
