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
        Schema::create('facturacion', function (Blueprint $table) {
            $table->id();
            $table->integer('folio')->unique();
            $table->enum('tipo_dte', ['33', '52', '56', '61']);
            $table->date('fecha_emision');
            $table->string('rut_receptor')->nullable();
            $table->string('razon_social_receptor')->nullable();
            $table->decimal('total_neto', 15, 2);
            $table->decimal('iva', 15, 2);
            $table->decimal('total', 15, 2);
            $table->enum('estado', ['emitida', 'anulada', 'pagada'])->default('emitida');
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('set null');
            $table->unsignedBigInteger('id_transferencia')->nullable();
            $table->foreign('id_transferencia')->references('id')->on('transferencias_bancarias')->onDelete('set null');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_factura'); 
        Schema::dropIfExists('facturacion');
    }
};
