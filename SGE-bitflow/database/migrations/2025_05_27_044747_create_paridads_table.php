<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        

        Schema::create('paridades', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // USD o UF
            $table->decimal('valor', 10, 2);
            $table->unsignedInteger('mes');
            $table->unsignedInteger('anio');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paridades');
    }
    
};
