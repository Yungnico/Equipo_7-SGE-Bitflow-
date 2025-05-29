<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transferencias_bancarias', function (Blueprint $table) {
            $table->id();

            $table->date('fecha_transaccion')->nullable();
            $table->time('hora_transaccion')->nullable();
            $table->date('fecha_contable')->nullable();

            $table->string('codigo_transferencia')->nullable();
            $table->string('tipo_transaccion')->nullable();
            $table->string('glosa_detalle')->nullable();

            $table->decimal('ingreso', 15, 2)->nullable();
            $table->decimal('egreso', 15, 2)->nullable();
            $table->decimal('saldo_contable', 15, 2)->nullable();

            $table->string('nombre')->nullable();
            $table->string('rut')->nullable();
            $table->string('numero_cuenta')->nullable();
            $table->string('tipo_cuenta')->nullable();
            $table->string('banco')->nullable();
            $table->string('comentario_transferencia')->nullable();

            $table->enum('estado', ['Pendiente', 'Conciliada'])->default('Pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transferencias_bancarias');
    }
};
