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
        $usuario = session('usuario');
        $tipo = $usuario['tipo'];
        $rut = $usuario['rut'];
        $tabla = $tipo === 'estudiante' ? 'estudiantes' : 'externos';

        $request->validate([
            'correo' => $tipo === 'externo' ? 'required|email' : '',
            'cargo' => $tipo === 'externo' ? 'nullable|string|max:100' : '',
            'institucion' => $tipo === 'externo' ? 'nullable|string|max:100' : '',
            'contrasena' => 'nullable|min:8|confirmed',
        ]);

        $datosActualizados = [];

        if ($tipo === 'externo') {
            $datosActualizados['correo'] = $request->correo;
            $datosActualizados['cargo'] = $request->cargo;
            $datosActualizados['institucion'] = $request->institucion;
        }

        if ($request->filled('contrasena')) {
            $datosActualizados['contrasena'] = Hash::make($request->contrasena);
        }

        DB::table($tabla)->where('rut', $rut)->update($datosActualizados);

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
