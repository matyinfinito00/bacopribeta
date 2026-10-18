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
        Schema::create('expensas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comunidad_id')->constrained('comunidades')->onDelete('cascade');
            $table->string('periodo');
            $table->date('vence_en');
            $table->decimal('monto', 12, 2);
            $table->string('banco');
            $table->string('cbu');
            $table->string('alias');
            $table->text('notas')->nullable();
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expensas');
    }
};
