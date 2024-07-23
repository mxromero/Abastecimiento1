<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $adminRoleId = 1; // ID del rol admin

        if (Auth::check() && Auth::user()->role_id == $adminRoleId) {
            return $next($request);
        }

        return redirect('/home')->with('error', 'No tienes acceso a esta sección');
    }
}

