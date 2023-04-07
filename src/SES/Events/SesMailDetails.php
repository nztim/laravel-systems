<?php declare(strict_types=1);

namespace NZTim\SES\Events;

use Carbon\Carbon;

class SesMailDetails
{
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function date(): Carbon
    {
        // SES changes message date header to UTC
        // In the webhook time is recorded in ISO format: 2016-10-19T23:20:52.240Z
        // 'Z' means Zulu or UTC time, need to move to local
        // Also, it seems that sometimes the date is provided as an array instead of a string, in this case use now() i.e. the time the notification is received
        $data = array_get($this->data, 'timestamp');
        return is_string($data) ?  Carbon::parse($data)->setTimezone('Pacific/Auckland') : now();
    }

    public function messageId(): string
    {
        return array_get($this->data, 'messageId', '');
    }

    public function envelopeFrom(): string
    {
        return array_get($this->data, 'source', '');
    }

    public function headerFrom(): string
    {
        return array_get($this->data, 'commonHeaders.from.0', '');
    }

    public function arn(): string
    {
        return array_get($this->data, 'sourceArn', '');
    }

    public function sourceIp(): string
    {
        return array_get($this->data, 'sourceIp', '');
    }

    public function recipient(): string
    {
        $destinations = array_get($this->data, 'destination', []);
        return $destinations[0] ?? '';
    }

    public function subject(): string
    {
        return array_get($this->data, 'commonHeaders.subject', '(Unknown)');
    }

    public function headers(): array
    {
        return array_get($this->data, 'headers', []);
    }

    public function header(string $key): string
    {
        foreach ($this->headers() as $header) {
            if (($header['name'] ?? '') === $key) {
                $value = $header['value'] ?? '';
                return is_string($value) ? $value : var_export($value, true);
            }
        }
        return '';
    }
}

/**
 * https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#mail-object
 */
