<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cotizacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cotizacion'); 
            $table->foreign('id_cotizacion')->references('id_cotizacion')->on('cotizaciones')->onDelete('cascade');
            $table->string('estado');
            $table->text('motivo')->nullable();
            $table->string('archivo')->nullable(); // ruta del archivo
            $table->string('factura_asociada')->nullable(); // podría ser FK si manejas facturas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizacion_detalles');
    }
};
