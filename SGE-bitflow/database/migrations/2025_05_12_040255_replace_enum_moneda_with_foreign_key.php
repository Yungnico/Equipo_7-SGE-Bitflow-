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
                $table->foreign('moneda_id')->references('id')->on('paridades')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            if (Schema::hasColumn('servicios', 'moneda_id')) {
                $table->dropForeign(['moneda_id']);
                $table->dropColumn('moneda_id');
            } 
        });
    }
};
