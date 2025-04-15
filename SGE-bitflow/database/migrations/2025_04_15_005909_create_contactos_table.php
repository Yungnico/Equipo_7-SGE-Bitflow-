<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_contacto', 50)->nullable();
            $table->string('email_contacto')->unique()->nullable();
            $table->string('telefono_contacto', 15)->nullable();
            $table->enum('tipo_contacto', ['Comercial', 'TI', 'Contable'])->nullable();
            $table->unsignedBigInteger('cliente_id'); // relación con cliente
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }
    /**
     * Run the migrations.
     */
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};
