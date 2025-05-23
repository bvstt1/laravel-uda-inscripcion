<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->string('rut_usuario');
            $table->unsignedBigInteger('id_evento');
            $table->string('tipo_usuario');
            $table->timestamp('fecha_inscripcion')->default(now());
            
            $table->foreign('id_evento')->references('id')->on('eventos')->onDelete('cascade');
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};