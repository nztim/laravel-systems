<?php declare(strict_types=1);

namespace NZTim\CommandBus\Laravel;

use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Application;
use NZTim\CommandBus\Middleware;

class LoggingMiddleware implements Middleware
{
    private bool $console;
    private AuthManager $auth;

    public function __construct(Application $app, AuthManager $auth)
    {
        $this->console = $app->runningInConsole();
        $this->auth = $auth;
    }

    public function execute(object $command, callable $next)
    {
        $user = $this->auth->guard()->user();
        $start = microtime(true);
        $response = $next($command);
        $seconds = number_format(microtime(true) - $start, 1);
        $message = "{$seconds}s | " . get_class($command);
        if ($this->console) {
            $message .= ' | console';
        }  elseif($user) {
            $message .= ' | ' . $user->email . ' (id:' . $user->id . ')';
        }
        log_info('bus', $message);
        return $response;
    }
}
