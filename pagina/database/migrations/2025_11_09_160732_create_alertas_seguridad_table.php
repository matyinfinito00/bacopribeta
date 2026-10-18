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
        Schema::create('alertas_seguridad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comunidad_id')->constrained('comunidades')->onDelete('cascade');
            $table->foreignId('administrador_id')->constrained('administradores')->onDelete('cascade');
            $table->string('titulo'); // Título de la alerta
            $table->text('contenido'); // Contenido de la alerta
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente']); // Prioridad de la alerta
            $table->timestamp('publicado_en')->nullable(); // Fecha de publicación
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas_seguridad');
    }
};
