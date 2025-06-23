<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subcategorias_costos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategorias_costos');
    }
};
