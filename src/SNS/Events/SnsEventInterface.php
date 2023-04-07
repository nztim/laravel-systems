<?php namespace NZTim\SNS\Events;

namespace NZTim\SNS\Events;

/**
 * @property array $data;
 * @property string $arn;
 * @property string $message;
 */
interface SnsEventInterface
{
    public static function fromArray(array $data): SnsEventInterface;
}

/*
 * https://docs.aws.amazon.com/sns/latest/dg/sns-message-and-json-formats.html
 */
