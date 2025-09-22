<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroEstudianteController;
use App\Http\Controllers\RegistroExternoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\AdminInscripcionController;
use App\Http\Controllers\TotemController;
use App\Http\Controllers\TotemLibreController;
use App\Http\Controllers\AdminAsistenciaController;
use App\Http\Controllers\CuentaUsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\RecuperarContrasenaController;
use App\Http\Controllers\HomeController;

// =======================
// HOME (Login principal)
// =======================
Route::get('/', function () {
    return view('welcome');
});

// =======================
// AUTENTICACIÓN
// =======================
Route::view('/login', 'login')->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// =======================
// SELECCIÓN DE USUARIO
// =======================
Route::view('/userSelection', 'userSelection')->name('userSelection');

// =======================
// REGISTRO DE USUARIOS
// =======================
Route::view('/registroEstudiante', 'registroEstudiante')->name('registroEstudiante');
Route::view('/registroExterno', 'registroExterno')->name('registroExterno');
Route::post('/registroEstudiante', [RegistroEstudianteController::class, 'store']);
Route::post('/registroExterno', [RegistroExternoController::class, 'store']);

// =======================
// RECUPERACIÓN DE CONTRASEÑA
// =======================
Route::get('/recuperarContrasena', [RecuperarContrasenaController::class, 'formularioSolicitud'])->name('password.request');
Route::post('/recuperarContrasena', [RecuperarContrasenaController::class, 'enviarCorreo'])->name('password.email');
Route::get('/restablecerContrasena/{token}', [RecuperarContrasenaController::class, 'formularioNueva'])->name('password.reset');
Route::post('/restablecerContrasena', [RecuperarContrasenaController::class, 'actualizarContrasena'])->name('password.update');

// =======================
// RUTAS PROTEGIDAS POR SESIÓN
// =======================
Route::middleware('session.auth')->group(function () {

    // PANEL DE ADMINISTRACIÓN
    Route::view('/admin/panel', 'admin.panel')->name('panel');

    // CRUD DE EVENTOS
    Route::prefix('admin/eventos')->name('eventos.')->group(function () {
        Route::get('/', [EventoController::class, 'index'])->name('index');
        Route::get('/crear', [EventoController::class, 'create'])->name('create');
        Route::post('/', [EventoController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [EventoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventoController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventoController::class, 'destroy'])->name('destroy');
    });

    // CREAR CATEGORÍAS (desde modal)
    Route::post('/admin/categorias', [CategoriaController::class, 'store'])->name('categorias.store');

    // FECHAS RELACIONADAS A EVENTOS SEMANALES
    Route::get('/eventos/fechas-semana/{id}', [EventoController::class, 'fechasSemana'])->name('eventos.dias');
    Route::get('/eventos/semanal/{id}/dias', [EventoController::class, 'verDias'])->name('eventos.semanal.dias');

    // INSCRIPCIÓN A EVENTOS
    Route::post('/eventos/{id}/inscribirse', [InscripcionController::class, 'store'])->name('inscribirse');
    Route::delete('/evento/{id}/desinscribirse', [InscripcionController::class, 'destroy'])->name('desinscribirse');

    // ADMINISTRACIÓN DE INSCRIPCIONES
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/ver-inscripciones', [AdminInscripcionController::class, 'verEventos'])->name('inscripciones');
        Route::get('/inscripciones/semana/{id}', [AdminInscripcionController::class, 'verDiasEventoSemanal'])->name('inscritos.semana');
        Route::get('/inscripciones/evento/{id}', [AdminInscripcionController::class, 'verInscritosPorEvento'])->name('inscritos.evento');
        Route::get('/exportar-excel/{id}', [AdminInscripcionController::class, 'exportarExcel'])->name('exportar.excel');
        Route::get('/eventos-administrar', [EventoController::class, 'administrarEventos'])->name('eventos.administrar');
        Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']);
    });

    // VISTA DE INSCRIPCIÓN PARA USUARIOS LOGUEADOS
    Route::get('/eventos-disponibles', [EventoController::class, 'mostrarEventosUsuarios'])->name('inscripcionEventos');
    Route::get('/eventos/semanal/{id}/ver-dias', [EventoController::class, 'verDiasUsuario'])->name('usuario.evento.dias');

    // TOTEM
    Route::get('/totem', [TotemController::class, 'seleccionarEvento'])->name('totem.selector');
    Route::get('/totem/evento/{id}', [TotemController::class, 'form'])->name('totem.form');
    Route::post('/totem/evento/{id}/asistencia', [TotemController::class, 'registrarAsistencia'])->name('totem.registrar');
    Route::get('/totem/eventos', [EventoController::class, 'seleccionarEventoTotem'])->name('totem.seleccionar');

    // TOTEM LIBRE
    Route::get('/totem/libre', [TotemLibreController::class, 'index'])->name('totem.libre');
    Route::post('/totem/libre', [TotemLibreController::class, 'registrar'])->name('totem.registro.libre');

    // ASISTENCIAS
    Route::get('/admin/asistencias', [AdminAsistenciaController::class, 'mostrarFormulario'])->name('admin.asistencias.filtro');
    Route::get('/admin/asistencias/buscar', [AdminAsistenciaController::class, 'filtrarAsistencias'])->name('admin.asistencias.buscar');
    Route::get('/admin/asistencias/exportar', [AdminAsistenciaController::class, 'exportarExcel'])->name('admin.asistencias.exportar');

    // CUENTA DE USUARIO
    Route::get('/mi-cuenta', [CuentaUsuarioController::class, 'mostrarFormulario'])->name('cuenta.formulario');
    Route::post('/mi-cuenta/actualizar', [CuentaUsuarioController::class, 'actualizar'])->name('cuenta.actualizar');
    Route::delete('/mi-cuenta/eliminar', [CuentaUsuarioController::class, 'eliminar'])->name('cuenta.eliminar');
});
