<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'rut' => '209950456',
            'contrasena' => Hash::make('admin123qr6(PS?0QWp6'),
        ]);
    }
}
