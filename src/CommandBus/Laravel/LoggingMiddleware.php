<?php declare(strict_types=1);

namespace NZTim\CommandBus\Laravel;

use App\Models\User\AuthUser;
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
        /** @var AuthUser $user */
        $user = $this->auth->guard()->user();
        $message = get_class($command) . ' | ';
        if ($this->console) {
            $message .= 'console';
        } elseif ($user) {
            $message .= $user->email . ' (id:' . $user->id . ')';
        } else {
            $message .= '(not logged in)';
        }
        log_info('bus', $message);
        return $next($command);
    }
}
