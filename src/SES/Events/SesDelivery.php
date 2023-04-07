<?php declare(strict_types=1);

namespace NZTim\SES\Events;

class SesDelivery implements SesEvent
{
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sesMail(): SesMailDetails
    {
        return new SesMailDetails($this->data['mail']);
    }
}

/**
 * This message contains a delivery object which can be modelled if needed, details are here:
 * https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#delivery-object
 */
