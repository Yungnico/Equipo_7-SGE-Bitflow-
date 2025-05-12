<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn('moneda'); // elimina el campo enum o string existente
            $table->foreignId('moneda_id')->constrained()->onDelete('cascade'); // nuevo campo como clave foránea
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropForeign(['moneda_id']);
            $table->dropColumn('moneda_id');
            $table->enum('moneda', ['USD', 'PEN', 'EUR']); // ajusta los valores según lo que usabas antes
        });
    }
};
