<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroEstudianteController;
use App\Http\Controllers\RegistroExternoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EventoController;

//
// ==========================
// RUTAS DE AUTENTICACIÓN
// ==========================
//

Route::view('/login', 'login')->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::view('/userSelection', 'userSelection')->name('userSelection');

//
// ==========================
// REGISTRO DE USUARIOS
// ==========================
//

Route::view('/registroEstudiante', 'registroEstudiante')->name('registroEstudiante');
Route::view('/registroExterno', 'registroExterno')->name('registroExterno');

Route::post('/registroEstudiante', [RegistroEstudianteController::class, 'store']);
Route::post('/registroExterno', [RegistroExternoController::class, 'store']);

//
// ==========================
// RUTAS PÚBLICAS / USUARIOS (Sin middleware)
// ==========================
//

Route::get('/eventos-disponibles', [EventoController::class, 'mostrarEventosUsuarios'])->name('inscripcionEventos');
Route::get('/eventos/semanal/{id}/ver-dias', [EventoController::class, 'verDiasUsuario'])->name('usuario.evento.dias');

//
// ==========================
// RUTAS DE ADMIN (Protegidas por middleware)
// ==========================
//

Route::middleware('session.auth')->group(function () {

    Route::view('/admin/panel', 'admin.panel')->name('panel');

    // CRUD de Eventos
    Route::get('/admin/eventos', [EventoController::class, 'index'])->name('eventos.index');
    Route::get('/admin/eventos/crear', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/admin/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/admin/eventos/{id}/editar', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/admin/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/admin/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');

    // Utilidad para seleccionar fechas válidas de una semana
    Route::get('/eventos/fechas-semana/{id}', [EventoController::class, 'fechasSemana'])->name('eventos.dias');
    Route::get('/eventos/semanal/{id}/dias', [EventoController::class, 'verDias'])->name('eventos.semanal.dias');
});




