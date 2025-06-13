<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transferencias_bancarias', function (Blueprint $table) {
            $table->enum('tipo_movimiento', ['ingreso', 'egreso'])->after('comentario_transferencia');
        });
    }

    public function down(): void
    {
        Schema::table('transferencias_bancarias', function (Blueprint $table) {
            $table->dropColumn('tipo_movimiento');
        });
    }
};
