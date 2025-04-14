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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 100)->unique();
            $table->string('rut', 12)->unique(); // 12 es suficiente para el formato XX.XXX.XXX-X
            $table->string('nombre_fantasia', 100)->nullable();
            $table->string('giro', 100)->nullable();
            $table->string('direccion', 150)->nullable();
            $table->string('logo')->nullable(); // Ruta de la imagen
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
