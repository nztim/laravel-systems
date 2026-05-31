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

    public function handle(MessageLogged $messageLogged): void
    {
        // Ignore unavoidable and useless message.
        if (str_contains($messageLogged->message, 'Directly setting property "headers" of "Illuminate\Http\Response" is deprecated; pass the header bag as a constructor argument instead.')) {
            return;
        }
        $this->logger->add('laravel', $this->logger->translateLevel($messageLogged->level), $messageLogged->message, $messageLogged->context);
    }
}
