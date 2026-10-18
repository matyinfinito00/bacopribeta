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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residente_id')->nullable()->constrained('residentes')->onDelete('cascade');
            $table->foreignId('administrador_id')->nullable()->constrained('administradores')->onDelete('cascade');
            $table->foreignId('personal_seguridad_id')->nullable()->constrained('personal_seguridad')->onDelete('set null');
            $table->foreignId('comunidad_id')->constrained('comunidades')->onDelete('cascade');
            $table->enum('tipo', [
                'agua',
                'areas_verdes',
                'iluminacion',
                'infraestructura',
                'mascotas_plagas',
                'servicios_suministros',
                'emergencias_medicas'
            ]);
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('ubicacion')->nullable();
            $table->string('imagen')->nullable();
            $table->enum('estado', ['pendiente', 'en_proceso', 'resuelta'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
