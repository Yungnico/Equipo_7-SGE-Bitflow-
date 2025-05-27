<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('paridades', function (Blueprint $table) {
            $table->id();
            $table->string('moneda', 10);           // Ej: USD, EUR
            $table->decimal('valor', 10, 4);        // Tasa de cambio con decimales
            $table->date('fecha');                  // Fecha de la paridad
            $table->timestamps();

            $table->unique(['moneda', 'fecha']);    // No puede repetirse la combinación
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paridades');
    }
};

