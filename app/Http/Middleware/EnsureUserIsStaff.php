<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Пускает в раздел /admin администраторов и кураторов. Конкретные права
 * (какие разделы доступны) и область по курсам проверяются уже внутри
 * каждого Livewire-компонента через User::hasPermission()/hasCourseAccess().
 */
class EnsureUserIsStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user?->isAdmin() || $user?->isCurator(), 403);

        return $next($request);
    }
}
