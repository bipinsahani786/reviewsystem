<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAgent
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || (! $user->isAgent() && ! $user->isSuperAdmin())) {
            abort(403, 'Unauthorized access. This portal is reserved for registered Sales Agents.');
        }

        return $next($request);
    }
}
