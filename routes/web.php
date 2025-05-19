<?php
use Illuminate\Support\Facades\Route;


# Register web routes for the application.
Route::get('/', function () {
    return view('index');
});

Route::get('/userSelection', function () {
    return view('userSelection');
});

Route::get('/registerEstudiante', function () {
    return view('registerEstudiante');
});

Route::post('/registerEstudiante', function () {
    return view('registerEstudiante');
});

Route::get('/registerExterno', function () {
    return view('registerExterno');
});

Route::post('/registerExterno', function () {
    return view('registerExterno');
});
