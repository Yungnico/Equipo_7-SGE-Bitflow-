<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('servicios', 'moneda_id')) {
            Schema::table('servicios', function (Blueprint $table) {
                $table->unsignedBigInteger('moneda_id')->after('id'); // Ajusta la posición si es necesario
            });
        }

        Schema::table('servicios', function (Blueprint $table) {
            $table->foreign('moneda_id')->references('id')->on('monedas')->onDelete('cascade');
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
