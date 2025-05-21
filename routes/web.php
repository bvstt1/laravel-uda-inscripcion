<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroEstudianteController;
use App\Http\Controllers\RegistroExternoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ImagenController;


# Register web views for the application.

Route::view('/login', "login")->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::view('/userSelection', "userSelection")->name('userSelection');

Route::get('/registroEstudiante', function () {
    return view('registroEstudiante');
})->name('registroEstudiante');

Route::get('/registroExterno', function () {
    return view('registroExterno');
})->name('registroExterno');

Route::post('/registroEstudiante', [RegistroEstudianteController::class, 'store']);
Route::post('/registroExterno', [RegistroExternoController::class, 'store']);

Route::middleware('session.auth')->group(function () {
    Route::view('/admin/panel', 'admin.panel')->name('panel');
    
    Route::view('/user/inscripcionEventos', 'user.inscripcionEventos')->name('inscripcionEventos');

    Route::get('/admin/eventos/crear', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/admin/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/fechas-semana/{id}', [EventoController::class, 'fechasSemana']);
    Route::post('/upload-imagen', [ImagenController::class, 'upload']);

});




