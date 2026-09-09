<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The blog admin's door. Same shape as SafeguardingMiddleware: the routes it
 * guards accept the narrow role and admins, so a content writer reaches the
 * posts screens and nothing else, and no admin loses anything.
 */
class EditorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !(auth()->user()->isEditor() || auth()->user()->isAdmin())) {
            abort(403, 'Unauthorized. Editor access required.');
        }

        return $next($request);
    }
}
