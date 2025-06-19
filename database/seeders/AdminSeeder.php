<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{

    public function run()
    {
        DB::table('admins')->where('rut', '209950456')->delete();

        Admin::create([
            'rut' => '209950456',
            'contrasena' => bcrypt('123456'),
        ]);
    }
    
}
