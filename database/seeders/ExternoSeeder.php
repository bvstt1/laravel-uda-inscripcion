<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Externo;
use Illuminate\Support\Facades\Hash;

class ExternoSeeder extends Seeder
{
    public function run(): void
    {
        Externo::firstOrCreate(
            ['rut' => '987654321'],
            [
                'correo' => 'externo1@institucion.cl',
                'institucion' => 'Universidad X',
                'cargo' => 'Coordinador de Vinculación',
                'contrasena' => Hash::make('123')
            ]
        );
    }
}
