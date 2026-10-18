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
        Schema::create('expensa_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expensa_id')->constrained('expensas')->onDelete('cascade');
            $table->foreignId('residente_id')->constrained('residentes')->onDelete('cascade');
            $table->enum('estado', ['pendiente', 'pagado'])->default('pendiente');
            $table->decimal('monto', 12, 2)->default(0);
            $table->timestamp('pagado_en')->nullable();
            $table->string('comprobante_url')->nullable();
            $table->timestamps();

            $table->unique(['expensa_id', 'residente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expensa_pagos');
    }
};
