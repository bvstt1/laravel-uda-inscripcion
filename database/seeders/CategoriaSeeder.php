<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::updateOrCreate(
            ['id' => 1],
            ['nombre' => 'Sin categoría', 'color' => '#CBD5E0']
        );
    }
}
