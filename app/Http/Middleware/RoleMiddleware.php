<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,kasir')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $userRole = (string) ($user->role ?? '');

        // allow multiple roles: role:admin,kasir
        if (! in_array($userRole, $roles, true)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}