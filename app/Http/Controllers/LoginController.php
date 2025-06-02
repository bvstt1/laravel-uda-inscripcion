<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Estudiante;
use App\Models\Externo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'rut' => 'required',
            'contrasena' => 'required',
        ]);

        $user = Admin::where('rut', $request->rut)->first();
        $tipo = 'admin';

        if (!$user) {
            $user = Estudiante::where('rut', $request->rut)->first();
            $tipo = 'estudiante';
        }

        if (!$user) {
            $user = Externo::where('rut', $request->rut)->first();
            $tipo = 'externo';
        }

        if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
            return back()->with('error', 'RUT o contraseña incorrectos.')->withInput();
        }


        Session::put('rut', $user->rut);
        Session::put('tipo_usuario', $tipo);


        // Redirigir según tipo
        if ($tipo === 'admin') {
            return redirect()->route('panel');
        } elseif ($tipo === 'estudiante') {
            return redirect()->route('inscripcionEventos');
        } else {
            return redirect()->route('inscripcionEventos');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
    
}
