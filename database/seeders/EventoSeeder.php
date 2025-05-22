<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;
use Carbon\Carbon;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear evento semanal
        $eventoSemanal = Evento::create([
            'tipo' => 'semanal',
            'titulo' => 'Semana de Bienvenida',
            'fecha' => Carbon::parse('2025-06-03'), // Lunes
            'hora' => '09:00',
            'hora_termino' => '13:00',
            'lugar' => 'Auditorio Central',
            'descripcion' => 'Actividades de bienvenida para estudiantes nuevos.',
            'id_evento_padre' => null
        ]);

        // Evento diario SIN semana asociada
        Evento::create([
            'tipo' => 'diario',
            'titulo' => 'Charla de seguridad',
            'fecha' => Carbon::parse('2025-06-04'),
            'hora' => '11:00',
            'hora_termino' => '12:30',
            'lugar' => 'Sala 201',
            'descripcion' => 'Charla informativa sobre seguridad universitaria.',
            'id_evento_padre' => null
        ]);

        // Evento diario ASOCIADO al evento semanal
        Evento::create([
            'tipo' => 'diario',
            'titulo' => 'Taller de orientación',
            'fecha' => Carbon::parse('2025-06-05'),
            'hora' => '10:00',
            'hora_termino' => '12:00',
            'lugar' => 'Sala 101',
            'descripcion' => 'Taller para conocer los servicios estudiantiles.',
            'id_evento_padre' => $eventoSemanal->id
        ]);

        Evento::create([
            'tipo' => 'diario',
            'titulo' => 'Tour por la facultad',
            'fecha' => Carbon::parse('2025-06-06'),
            'hora' => '15:00',
            'hora_termino' => '16:30',
            'lugar' => 'Entrada principal',
            'descripcion' => 'Recorrido guiado por las instalaciones.',
            'id_evento_padre' => $eventoSemanal->id
        ]);
    }
}
