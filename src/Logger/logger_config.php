<?php declare(strict_types=1);

return [
    'laravel'   => true, // Capture Laravel log entries
    'single'    => [], // Single log files (daily is default)
    'max_daily' => 7, // Maximum number of daily log files to keep
    'email'     => [
        'send' => false, // Sending of error emails
        'from' => env('LOGGER_EMAIL_SENDER', 'sender@example.com'),
        'to'   => env('LOGGER_EMAIL_RECIPIENT', 'recipient@example.com'),
    ],
];

