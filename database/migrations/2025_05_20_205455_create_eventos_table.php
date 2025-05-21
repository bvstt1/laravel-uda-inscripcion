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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['semanal', 'diario']);
            $table->string('titulo');
            $table->date('fecha');
            $table->string('lugar');
            $table->time('hora')->nullable(); // hora inicio (solo para diario)
            $table->time('hora_termino')->nullable(); // nueva hora término
            $table->text('descripcion');
            $table->foreignId('id_evento_padre')->nullable()->constrained('eventos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
