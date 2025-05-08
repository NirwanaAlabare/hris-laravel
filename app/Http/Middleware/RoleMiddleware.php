<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->modul, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
