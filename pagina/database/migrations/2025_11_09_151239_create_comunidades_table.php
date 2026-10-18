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
        Schema::create('comunidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del barrio/country
            $table->text('descripcion')->nullable(); // Descripción del barrio
            $table->string('direccion'); // Dirección del barrio
            $table->string('ciudad'); // Ciudad
            $table->string('provincia'); // Provincia
            $table->string('codigo_postal'); // Código postal
            $table->string('telefono'); // Teléfono de contacto
            $table->string('correo'); // Email de contacto
            $table->boolean('estado')->default(true); // Estado activo/inactivo
            $table->decimal('mapa_center_lat', 10, 7)->nullable(); // Latitud del centro del mapa
            $table->decimal('mapa_center_lng', 10, 7)->nullable(); // Longitud del centro del mapa
            $table->unsignedSmallInteger('mapa_zoom')->nullable(); // Nivel de zoom del mapa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunidades');
    }
};