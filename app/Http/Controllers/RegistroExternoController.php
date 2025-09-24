<?php

namespace App\Http\Controllers;

use App\Models\Externo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class RegistroExternoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
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
                function ($attribute, $value, $fail) {
                    $existe = DB::table('estudiantes')->where('correo', $value)->exists()
                        || DB::table('externos')->where('correo', $value)->exists();

                    if ($existe) {
                        $fail('Ya existe una cuenta registrada con este correo.');
                    }
                }
            ],
            'institucion' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'contrasena' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ]
        ]);

        $rut_normalizado = \Freshwork\ChileanBundle\Rut::parse($request->rut)->normalize();

        $externo = Externo::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'rut' => $rut_normalizado,
            'correo' => $request->correo,
            'institucion' => $request->institucion,
            'cargo' => $request->cargo,
            'contrasena' => Hash::make($request->contrasena)
        ]);

        Session::put('rut', $externo->rut);
        Session::put('tipo_usuario', 'externo');

        return redirect()->route('inscripcionEventos')->with('success', 'Usuario registrado exitosamente');
    }
}
