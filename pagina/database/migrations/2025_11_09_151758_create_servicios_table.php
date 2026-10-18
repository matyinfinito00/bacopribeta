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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comunidad_id')->constrained('comunidades')->onDelete('cascade');
            $table->string('nombre'); // Nombre del servicio
            $table->text('descripcion')->nullable(); // Descripción
            $table->enum('categoria', ['emergencia', 'mantenimiento', 'seguridad', 'limpieza', 'jardineria', 'otro']); // Categoría
            $table->string('nombre_contacto'); // Nombre del contacto
            $table->string('telefono'); // Teléfono
            $table->string('correo'); // Email
            $table->string('direccion'); // Dirección
            $table->json('horario'); // Horarios de atención
            $table->boolean('es_emergencia')->default(false); // Si es servicio de emergencia
            $table->boolean('estado')->default(true); // Estado activo/inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};