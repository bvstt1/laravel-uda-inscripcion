<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class RegistroEstudianteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rut' => [
                'required',
                'cl_rut',
                function ($attribute, $value, $fail) {
                    $rut_normalizado = \Freshwork\ChileanBundle\Rut::parse($value)->normalize();

                    $existe = DB::table('estudiantes')->where('rut', $rut_normalizado)->exists()
                        || DB::table('externos')->where('rut', $rut_normalizado)->exists();

                    if ($existe) {
                        $fail('Ya existe una cuenta registrada con este RUT.');
                    }
                },
            ],
            'correo' => [
                'required',
                'email',
                'ends_with:@alumnos.uda.cl',
                function ($attribute, $value, $fail) {
                    $existe = DB::table('estudiantes')->where('correo', $value)->exists()
                        || DB::table('externos')->where('correo', $value)->exists();

                    if ($existe) {
                        $fail('Ya existe una cuenta registrada con este correo.');
                    }
                }
            ],
            'carrera' => 'required|string|max:255',
            'contrasena' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ]
        ]);

        $rut_normalizado = \Freshwork\ChileanBundle\Rut::parse($request->rut)->normalize();

        $estudiante = Estudiante::create([
            'rut' => $rut_normalizado,
            'correo' => $request->correo,
            'carrera' => $request->carrera,
            'contrasena' => Hash::make($request->contrasena)
        ]);

        Session::put('rut', $estudiante->rut);
        Session::put('tipo_usuario', 'estudiante');

        return redirect()->route('inscripcionEventos')->with('success', 'Usuario registrado exitosamente');
    }
}
