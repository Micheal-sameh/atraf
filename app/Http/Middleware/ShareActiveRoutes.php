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
            'leaderboard' => str_contains($currentRoute, 'leaderboard'),
            'competitions' => str_contains($currentRoute, 'competitions.'),
            'quizzes' => str_contains($currentRoute, 'quizzes.'),
            'questions' => str_contains($currentRoute, 'questions.'),
            'bonus-penalties' => str_contains($currentRoute, 'bonus-penalties.'),
            'rewards' => str_contains($currentRoute, 'rewards.'),
            'orders' => str_contains($currentRoute, 'orders.'),
            'settings' => str_contains($currentRoute, 'settings.'),
            'groups' => str_contains($currentRoute, 'groups.'),
            'notifications' => str_contains($currentRoute, 'notifications.'),
            'about_us' => str_contains($currentRoute, 'about_us.'),
            'terms' => str_contains($currentRoute, 'terms.'),
            'social-media' => str_contains($currentRoute, 'social-media.'),
            'info-videos' => str_contains($currentRoute, 'info-videos.'),
            'father-schedules' => str_contains($currentRoute, 'father-schedules.'),
            'atraf' => str_contains($currentRoute, 'atraf.'),
            'families' => str_contains($currentRoute, 'families.'),
        ];

        View::share('activeRoutes', $activeRoutes);

        return $next($request);
    }
}
