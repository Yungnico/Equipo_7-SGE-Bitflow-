<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id('id_cotizacion');

            $table->unsignedBigInteger('id_cliente');
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');

            $table->decimal('total', 10, 2);
            $table->string('moneda', 3);
            $table->enum('estado', ['Borrador', 'Enviada', 'Aceptada', 'Facturada', 'Pagada', 'Anulada', 'Rechazada']);

            $table->date('fecha_cotizacion');
            $table->decimal('descuento', 10, 2)->nullable();

            $table->unsignedBigInteger('id_transferencia')->nullable();
            $table->foreign('id_transferencia')->references('id')->on('transferencias_bancarias')->onDelete('set null');

            $table->timestamps();
        });

        DB::statement('ALTER TABLE cotizaciones AUTO_INCREMENT = 1000');
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
