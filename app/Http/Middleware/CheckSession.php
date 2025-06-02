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

        // Asegura que también exista 'usuario' completo en la sesión
        if (!Session::has('usuario')) {
            $rut = Session::get('rut');
            $tipo = Session::get('tipo_usuario');

            Session::put('usuario', [
                'rut' => $rut,
                'tipo' => $tipo
            ]);
        }

        return $next($request);
    }

}

