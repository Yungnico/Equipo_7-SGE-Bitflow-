<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('paridades', function (Blueprint $table) {
            $table->id();
            $table->string('moneda', 10);
            $table->decimal('valor', 10, 4);
            $table->date('fecha');
            $table->timestamps();

            $table->unique(['moneda', 'fecha']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('paridades');
    }
};
