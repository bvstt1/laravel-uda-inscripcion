<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Hash;

class EstudianteSeeder extends Seeder
{
    public function run(): void
    {
        Estudiante::firstOrCreate(
            ['rut' => '123456789'],
            [
                'correo' => 'estudiante1@alumnos.uda.cl',
                'carrera' => 'Ingeniería Civil en Computación e Informática',
                'contrasena' => Hash::make('123')
            ]
        );
    }
}
