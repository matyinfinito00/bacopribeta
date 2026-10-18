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
        Schema::create('instalaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comunidad_id')->constrained('comunidades')->onDelete('cascade');
            $table->string('nombre'); // Nombre de la instalación
            $table->text('descripcion')->nullable(); // Descripción
            $table->enum('tipo', ['deportiva', 'gastronomia', 'eventos', 'recreacion', 'otra'])->nullable(); // Tipo de instalación
            $table->string('ubicacion')->nullable(); // Ubicación dentro del barrio
            $table->decimal('lat', 10, 7)->nullable(); // Latitud para mapa
            $table->decimal('lng', 10, 7)->nullable(); // Longitud para mapa
            $table->integer('capacidad')->nullable(); // Capacidad máxima
            $table->json('amenidades')->nullable(); // Amenidades o servicios adicionales disponibles en la instalación
            $table->json('imagenes')->nullable(); // Imágenes de la instalación
            $table->string('telefono'); // Teléfono de contacto
            $table->json('horario'); // Horarios de disponibilidad
            $table->boolean('es_reservable')->default(true); // Si es reservable
            $table->string('link_reserva')->nullable(); // Link externo de reserva (ej: para gastronomía)
            $table->boolean('estado')->default(true); // Estado activo/inactivo
            $table->json('reglas')->nullable(); // Reglas específicas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instalaciones');
    }
};