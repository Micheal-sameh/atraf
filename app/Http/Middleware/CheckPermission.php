<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (! auth()->check()) {
            abort(403, 'Unauthorized');
        }

        foreach ($permissions as $permission) {
            if (auth()->user()->can($permission)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this page');
    }
}
