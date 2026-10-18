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
        Schema::create('invitados', function (Blueprint $table) {
            $table->id(); // PK
            $table->foreignId('comunidad_id') // Comunidad del residente
                ->constrained('comunidades')
                ->onDelete('cascade');
            $table->foreignId('residente_id') // Residente que registra la invitacion
                ->constrained('residentes')
                ->onDelete('cascade');
            $table->string('nombre_completo'); // Nombre del invitado
            $table->string('numero_documento', 9); // Documento del invitado (máx 9 caracteres: DNI 8, Cédula 6-8, Pasaporte 9)
            $table->timestamp('visita_en'); // Fecha y hora de la visita
            $table->text('comentario')->nullable(); // Nota opcional
            $table->boolean('ingresa_con_auto')->default(false);  // Si el invitado ingresa con auto
            $table->string('patente')->nullable(); // Patente del auto (si ingresa con auto)
            $table->string('estado')->default('pendiente'); // Estado de la invitacion
            $table->timestamp('ingreso_en')->nullable(); // Hora de ingreso confirmado por seguridad
            $table->timestamp('salida_en')->nullable(); // Hora de salida registrada por seguridad
            $table->foreignId('confirmado_por_administrador_id')->nullable() // Quien confirmo el ingreso
                ->constrained('administradores')
                ->onDelete('set null');
            $table->foreignId('cancelado_por_administrador_id')->nullable() // Quien cancelo la invitacion
                ->constrained('administradores')
                ->onDelete('set null');
            $table->foreignId('salida_registrada_por_administrador_id')->nullable() // Quien registro la salida
                ->constrained('administradores')
                ->onDelete('set null');
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitados');
    }
};

