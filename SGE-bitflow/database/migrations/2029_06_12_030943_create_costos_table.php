<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('costos', function (Blueprint $table) {
            $table->id();
            $table->string('concepto');
            $table->foreignId('categoria_id')->constrained('categorias_costos');
            $table->enum('frecuencia_pago', ['único', 'mensual', 'trimestral', 'semestral', 'anual']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costos');
    }
};
