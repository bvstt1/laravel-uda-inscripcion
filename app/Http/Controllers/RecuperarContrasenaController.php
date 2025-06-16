<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Estudiante;
use App\Models\Externo;
use App\Mail\RecuperarContrasenaMail;

class RecuperarContrasenaController extends Controller
{
    public function formularioSolicitud()
    {
        return view('recuperarContrasena');
    }

    public function enviarCorreo(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'tipo' => 'required|in:estudiante,externo'
        ]);

        $email = $request->email;
        $tipo = $request->tipo;
        $usuario = null;

        if ($tipo === 'estudiante') {
            $usuario = Estudiante::where('correo', $email)->first();
        } elseif ($tipo === 'externo') {
            $usuario = Externo::where('correo', $email)->first();
        }

        if (!$usuario) {
            return back()->with('status', 'Si el correo existe, se ha enviado un enlace de recuperación.');
        }

        $token = Str::random(64);
        $expira = Carbon::now()->addMinutes(60);

        DB::table('password_resets_custom')->insert([
            'email' => $email,
            'tipo' => $tipo,
            'token' => $token,
            'expires_at' => $expira,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $link = url("/restablecerContrasena/{$token}");

        // Enviar correo (ajusta el contenido si quieres personalizarlo más)

        Mail::to($email)->send(new RecuperarContrasenaMail($link));

        return back()->with('status', 'Si el correo existe, se ha enviado un enlace de recuperación.');
    }

    public function formularioNueva(string $token)
    {
        $registro = DB::table('password_resets_custom')->where('token', $token)->first();

        if (!$registro || Carbon::parse($registro->expires_at)->isPast()) {
            return redirect('/login')->withErrors(['token' => 'El enlace ha expirado o no es válido.']);
        }

        return view('restablecerContrasena', ['token' => $token]);
    }

    public function actualizarContrasena(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $registro = DB::table('password_resets_custom')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$registro || Carbon::parse($registro->expires_at)->isPast()) {
            return back()->withErrors(['email' => 'El token es inválido o ha expirado.']);
        }

        if ($registro->tipo === 'estudiante') {
            $usuario = Estudiante::where('correo', $request->email)->first();
        } else {
            $usuario = Externo::where('correo', $request->email)->first();
        }

        if (!$usuario) {
            return back()->withErrors(['email' => 'Usuario no encontrado.']);
        }

        $usuario->contrasena = Hash::make($request->password);
        $usuario->save();

        DB::table('password_resets_custom')->where('email', $request->email)->delete();

        return back()->with('contrasena_cambiada', true);
    }
}
