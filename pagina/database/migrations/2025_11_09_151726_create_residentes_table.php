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
        Schema::create('residentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comunidad_id')->constrained('comunidades')->onDelete('cascade');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->string('password');
            $table->string('telefono')->unique();
            $table->string('direccion'); // Calle
            $table->string('altura', 20)->nullable(); // Altura de la calle
            $table->string('numero_lote', 20)->nullable(); // Número de lote
            $table->string('numero_unidad'); // Número de unidad/casa
            $table->string('tipo_documento', 20)->default('dni');
            $table->string('numero_documento', 9);
            $table->date('fecha_nacimiento');
            $table->boolean('estado')->default(true);
            $table->boolean('es_propietario')->default(false);
            $table->string('foto_perfil')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Unicidad compuesta: mismo tipo+número de documento no puede repetirse
            $table->unique(['tipo_documento', 'numero_documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residentes');
    }
};