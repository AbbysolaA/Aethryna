<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // One-click unsubscribe is posted by the mail provider (Gmail, Apple
        // Mail), not by a browser holding a session, so there is no CSRF token
        // to send and RFC 8058 does not allow for one. Safe to exempt: the URL
        // carries its own unguessable token, and the only thing it can do is
        // stop us emailing that one assessment.
        $middleware->validateCsrfTokens(except: [
            'assessment/unsubscribe/*',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'coach' => \App\Http\Middleware\CoachMiddleware::class,
            'mentor' => \App\Http\Middleware\MentorMiddleware::class,
            'safeguarding' => \App\Http\Middleware\SafeguardingMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
