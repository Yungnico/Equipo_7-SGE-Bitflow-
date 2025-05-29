<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        

        Schema::create('paridades', function (Blueprint $table) {
            $table->string('moneda');
            $table->date('fecha');
            $table->unique(['moneda', 'fecha']);
            $table->id();
            $table->decimal('valor', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paridades');
    }
    
};

