<?php declare(strict_types=1);

namespace NZTim\Logger;

use Illuminate\Log\Events\MessageLogged;

class LaravelLogListener
{
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function handle(MessageLogged $messageLogged)
    {
        $this->logger->add('laravel', $this->logger->translateLevel($messageLogged->level), $messageLogged->message, $messageLogged->context);
    }
}
