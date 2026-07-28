<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates the safeguarding review screens.
 *
 * Admins pass because they already could. The point of the separate role is
 * the other direction: the safeguarding lead reaches these screens without
 * also inheriting the user list, content management and the risk register.
 */
class SafeguardingMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user || ! ($user->isAdmin() || $user->isSafeguardingLead())) {
            abort(403, 'Unauthorized. Safeguarding access required.');
        }

        return $next($request);
    }
}
