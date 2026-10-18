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
        Schema::create('novedades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comunidad_id')->constrained('comunidades')->onDelete('cascade');
            $table->foreignId('administrador_id')->constrained('administradores')->onDelete('cascade');
            $table->string('titulo'); // Título de la novedad
            $table->text('contenido'); // Contenido de la novedad
            $table->string('imagen')->nullable(); // Imagen de la novedad
            $table->timestamp('publicado_en')->nullable(); // Fecha de publicación
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novedades');
    }
};
