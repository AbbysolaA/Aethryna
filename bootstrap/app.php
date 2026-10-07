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
            // Same reasoning for the blog: mail providers POST the one-click
            // unsubscribe with no session and no token, and the URL's own
            // unguessable token is the authorisation.
            'blog/unsubscribe/*',
        ]);

        // Site-wide because ads will not always point at the front door: a
        // tagged link can land on the event page, the home page or a course
        // page, and the registration that follows can happen anywhere.
        $middleware->web(append: [
            \App\Http\Middleware\CaptureUtmAttribution::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'coach' => \App\Http\Middleware\CoachMiddleware::class,
            'mentor' => \App\Http\Middleware\MentorMiddleware::class,
            'safeguarding' => \App\Http\Middleware\SafeguardingMiddleware::class,
            'editor' => \App\Http\Middleware\EditorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A CV or photo bigger than PHP's own post_max_size dies before any
        // controller or validation rule runs, and the stock answer is a bare
        // 413 page: application lost, nothing saved, no explanation. Turn it
        // back into the form with a message a person can act on. Form input
        // cannot be flashed back because the request was never parsed.
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            $target = $request->headers->get('referer') ? back() : redirect('/');

            return $target->with('error', 'That did not go through: the attachment is larger than the server accepts. Please attach a file under 5MB and send it again.');
        });
    })->create();
