<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('rut') || !Session::has('tipo_usuario')) {
            return redirect('/login');
        }

        return $next($request);
    }
}

