<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Estudiante;
use App\Models\Externo;

class CuentaUsuarioController extends Controller
{
    public function mostrarFormulario()
    {
        $usuarioSession = session('usuario');

        if (!$usuarioSession) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        $rut = $usuarioSession['rut'];
        $tipo = $usuarioSession['tipo'];

        $usuario = $tipo === 'estudiante'
            ? Estudiante::where('rut', $rut)->first()
            : Externo::where('rut', $rut)->first();

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Usuario no encontrado.');
        }

        $usuario->tipo = $tipo; // Para usarlo en la vista

        return view('user.miCuenta', ['usuario' => $usuario]);
    }

    public function actualizar(Request $request)
    {
        $usuarioSession = session('usuario');
        $tipo = $usuarioSession['tipo'];
        $rut = $usuarioSession['rut'];

        // Obtener modelo correspondiente
        $usuario = $tipo === 'estudiante' 
            ? Estudiante::where('rut', $rut)->first() 
            : Externo::where('rut', $rut)->first();

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Usuario no encontrado.');
        }

        // Validación
        $rules = [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'contrasena' => 'nullable|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
        ];

        // Campos adicionales solo para externos
        if ($tipo === 'externo') {
            $rules['correo'] = 'required|email|max:255|unique:externos,correo,' . $usuario->id;
            $rules['cargo'] = 'nullable|string|max:100';
            $rules['institucion'] = 'nullable|string|max:100';
        }

        $request->validate($rules);

        // Actualizar campos comunes
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;

        // Actualizar contraseña si existe
        if ($request->filled('contrasena')) {
            $usuario->contrasena = Hash::make($request->contrasena);
        }

        // Actualizar campos adicionales solo para externos
        if ($tipo === 'externo') {
            $usuario->correo = $request->correo;
            $usuario->cargo = $request->cargo;
            $usuario->institucion = $request->institucion;
        }

        $usuario->save();

        return redirect()->route('cuenta.formulario')->with('success', 'Datos actualizados correctamente.');
    }

    public function eliminar(Request $request)
    {
        $usuario = session('usuario');
        $tipo = $usuario['tipo'];
        $rut = $usuario['rut'];
        $tabla = $tipo === 'estudiante' ? 'estudiantes' : 'externos';

        DB::table('inscripciones')->where('rut_usuario', $rut)->delete();
        DB::table($tabla)->where('rut', $rut)->delete();

        session()->forget('usuario');

        return redirect()->route('login')->with('success', 'Cuenta eliminada con éxito.');
    }
}
