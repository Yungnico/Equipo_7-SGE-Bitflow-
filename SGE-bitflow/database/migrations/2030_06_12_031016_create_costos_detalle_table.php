<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('costos_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('costo_id')->constrained('costos')->onDelete('cascade');
            $table->year('año');
            $table->foreignId('moneda_id')->constrained('paridades')->onDelete('cascade');
            $table->unsignedTinyInteger('mes')->nullable();
            $table->decimal('monto', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costos_detalle');
    }
};
