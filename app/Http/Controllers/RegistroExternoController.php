<?php

namespace App\Http\Controllers;

use App\Models\Externo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistroExternoController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'rut' => 'required|unique:externos',
            'correo' => 'required|email|unique:externos',
            'institucion' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'contrasena' => 'required|confirmed|min:6'
        ]);

        $externo = Externo::create([
            'rut' => $request->rut,
            'correo' => $request->correo,
            'institucion' => $request->institucion,
            'cargo' => $request->cargo,
            'contrasena' => Hash::make($request->contrasena)
        ]);

        Auth::login($externo);
        return redirect('/user/inscripcionEventos')->with('success', 'Estudiante registrado exitosamente');
    }
}