<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InscripcionesPorEventoExport implements WithMultipleSheets
{
    protected $eventoId;

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function sheets(): array
    {
        return [
            new EstudiantesSheet($this->eventoId),
            new ExternosSheet($this->eventoId),
        ];
    }
}

