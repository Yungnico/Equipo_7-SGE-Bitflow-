<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->boolean('estado')->default(true); // true = activo
        });
    }
    
    public function down()
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }

};
