<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistroEstudianteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rut' => 'required|unique:estudiantes',
            'correo' => 'required|email|ends_with:@alumnos.uda.cl|unique:estudiantes,correo',
            'carrera' => 'required',
            'contrasena' => 'required|confirmed|min:6'
        ]);

        $estudiante = Estudiante::create([
            'rut' => $request->rut,
            'correo' => $request->correo,
            'carrera' => $request->carrera,
            'contrasena' => Hash::make($request->contrasena)
        ]);

        Auth::login($estudiante);
        return redirect('/user/inscripcionEventos')->with('success', 'Estudiante registrado exitosamente');
    }
}