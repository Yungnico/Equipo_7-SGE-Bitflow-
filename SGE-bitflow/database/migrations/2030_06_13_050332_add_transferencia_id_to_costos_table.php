<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('costos', function (Blueprint $table) {
            $table->unsignedBigInteger('transferencias_bancarias_id')->nullable()->after('id');

            $table->foreign('transferencias_bancarias_id')
                ->references('id')
                ->on('transferencias_bancarias')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('costos', function (Blueprint $table) {
            $table->dropForeign(['transferencias_bancarias_id']);
            $table->dropColumn('transferencias_bancarias_id');
        });
    }
};
