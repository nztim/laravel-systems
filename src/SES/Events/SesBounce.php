<?php declare(strict_types=1);

namespace NZTim\SES\Events;

use RuntimeException;

class SesBounce implements SesEvent
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

    // Bounce object: https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object

    public function bounceType(): string
    {
        return array_get($this->data, 'bounce.bounceType', '');
    }

    public function bounceSubType(): string
    {
        return array_get($this->data, 'bounce.bounceSubType', '');
    }

    public function diagnosticCode(): string
    {
        return array_get($this->data, 'bounce.bouncedRecipients.0.diagnosticCode', '');
    }

    // Interpretation ---------------------------------------------------------

    public function classification(): BounceClassification
    {
        $type = $this->bounceType();
        $subType = $this->bounceSubType();
        if ($type === 'Undetermined') {
            return BounceClassification::Bounce;
        }
        if ($type === 'Permanent') {
            if ($subType === 'NoEmail') {
                return BounceClassification::NoEmail;
            }
            if (in_array($subType, ['OnAccountSuppressionList', 'Suppressed'])) {
                return BounceClassification::Suppressed;
            }
            return BounceClassification::Bounce;
        }
        if ($type === 'Transient') {
            if (in_array($subType, ['MessageTooLarge', 'ContentRejected', 'AttachmentRejected'])) {
                return BounceClassification::Rejected;
            }
            return BounceClassification::SoftFail;
        }
        throw new RuntimeException("Unknown bounceType: {$type}");
    }

    // If this is true and the message has previously been delivered then it's an OOO autoresponder failure.
    public function possibleAutoresponderFailure(): bool
    {
        return $this->bounceType() === 'Transient' && $this->bounceSubType() === 'General' && $this->diagnosticCode() === '';

    }
}

/**
 * https://docs.aws.amazon.com/ses/latest/dg/notification-contents.html#bounce-object
 */
