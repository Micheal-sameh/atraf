<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ShareActiveRoutes
{
    public function handle(Request $request, Closure $next)
    {
        $currentRoute = $request->route() ? $request->route()->getName() : '';

        $activeRoutes = [
            'users' => str_contains($currentRoute, 'users.'),
            'father-schedules' => str_contains($currentRoute, 'father-schedules.'),
            'atraf' => str_contains($currentRoute, 'atraf.'),
            'families' => str_contains($currentRoute, 'families.'),
            'settings' => str_contains($currentRoute, 'settings.'),
        ];

        View::share('activeRoutes', $activeRoutes);

        return $next($request);
    }
}
