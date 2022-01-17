<?php

namespace NZTim\Mailer;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use NZTim\CommandBus\CommandBus;

abstract class TestEmailsCommand extends Command
{
    protected $signature = 'testemails {recipient?}';

    protected $description = 'Send test emails';

    protected $recipient = 'test@example.org';

    /** @var AbstractMessage[] */
    protected array $tests = [];

    /** @return string[]|array - list of test class names */
    abstract protected function tests(): array;

    public function handle(): int
    {
        $this->setRecipient();
        $this->info("\nSending test emails to: " . $this->recipient . "\n");
        $this->setTests();
        $selection = $this->menu();
        if (array_has($this->tests, $selection)) {
            /** @var AbstractMessage $message */
            $message = array_get($this->tests, $selection);
            $this->info('Sending: ' . $message->testLabel());
            $message->recipientOverride = $this->recipient;
            $this->passToHandler($message);
            $this->info('Done');
            return 0;
        }
        if ($selection === 'all') {
            $this->info('Sending all tests:');
            $this->sendAll();
            return 0;
        }
        $this->warn('Selection not found!');
        return 1;
    }

    protected function setRecipient()
    {
        $recipient = $this->argument('recipient');
        if (filter_var($recipient, FILTER_VALIDATE_EMAIL) !== false) {
            $this->recipient = $recipient;
        }
    }

    // Sorted, 1-indexed list of test classes
    protected function setTests()
    {
        /** @var Collection $tests */
        $tests = collect($this->tests());
        $tests = $tests->map(function (string $className) {
            /** @noinspection PhpUndefinedMethodInspection */
            return $className::test();
        });
        $tests = $tests->sortBy(function (AbstractMessage $test) {
            return $test->testLabel();
        })->values();
        $tests = $tests->keyBy(function ($test, int $key) {
            return $key + 1;
        });
        $this->tests = $tests->toArray();
    }

    protected function menu(): string
    {
        foreach ($this->tests as $key => $test) {
            $this->info($key . ') ' . $test->testLabel());
        }
        return $this->ask("\nSelect message to send (or enter for all):", 'all');
    }

    protected function sendAll()
    {
        foreach ($this->tests as $message) {
            $this->info('Sending: ' . $message->testLabel());
            $message->recipientOverride = $this->recipient;
            $this->passToHandler($message);
            $this->info('Done');
        }
    }

    private function passToHandler(AbstractMessage $message): void
    {
        app(CommandBus::class)->handle($message);
        return;
    }
}
