<?php declare(strict_types=1);

namespace NZTim\SNS;

class Result
{
    public bool $success;
    public string $message;

    private function __construct() {}

    public static function createSuccess(string $message = ''): Result
    {
        $result = new Result();
        $result->success = true;
        $result->message = $message;
        return $result;
    }

    public static function createFailed(string $message): Result
    {
        $result = new Result();
        $result->success = false;
        $result->message = $message;
        return $result;
    }
}
