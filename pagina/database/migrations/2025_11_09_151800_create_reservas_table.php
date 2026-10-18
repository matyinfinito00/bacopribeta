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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residente_id')->constrained('residentes')->onDelete('cascade');
            $table->foreignId('instalacion_id')->constrained('instalaciones')->onDelete('cascade');
            $table->date('fecha'); // Fecha de la reserva
            $table->time('hora_inicio'); // Hora de inicio
            $table->integer('duracion_minutos')->default(60); // Duración en minutos
            $table->enum('estado', ['activa', 'cancelada', 'completada'])->default('activa');
            $table->timestamps();
            
            // Índice para búsquedas rápidas
            $table->index(['instalacion_id', 'fecha', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};

