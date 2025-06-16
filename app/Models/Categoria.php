<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'color', 'imagen'];

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
