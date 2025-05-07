<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('monedas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('valor', 10, 2); // Por ejemplo, 1 USD = 860 CLP
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monedas');
    }
};
