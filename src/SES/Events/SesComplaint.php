<?php declare(strict_types=1);

namespace NZTim\SES\Events;

use Illuminate\Support\Arr;

class SesComplaint implements SesEvent
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

    // Complaint object: https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#complaint-object

    public function complaintType(): string
    {
        return Arr::get($this->data, 'complaint.complaintFeedbackType', '');
    }
}

/**
 * This message contains a complaint object which can be modelled if required, details:
 * https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#complaint-object
 */
