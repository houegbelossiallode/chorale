<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Ne pas rediriger si on est déjà sur la page de changement ou si on se déconnecte
            if (!$request->routeIs('password.change', 'password.change.update') && !$request->is('logout') && !$request->expectsJson()) {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}
