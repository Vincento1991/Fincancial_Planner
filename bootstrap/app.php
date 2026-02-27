<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Disable CSRF verification — sessions don't persist on serverless
        $middleware->validateCsrfTokens(except: ['*']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// On Vercel (serverless), use /tmp for storage since filesystem is read-only
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || getenv('VERCEL')) {
    $app->useStoragePath('/tmp/storage');
}

return $app;
