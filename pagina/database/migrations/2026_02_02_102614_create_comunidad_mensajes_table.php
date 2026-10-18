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
        Schema::create('comunidad_mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residente_id')->constrained('residentes')->onDelete('cascade');
            $table->foreignId('comunidad_id')->constrained('comunidades')->onDelete('cascade');
            $table->text('mensaje')->nullable();
            $table->enum('tipo_archivo', ['texto', 'imagen', 'archivo', 'video'])->default('texto');
            $table->string('archivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunidad_mensajes');
    }
};
