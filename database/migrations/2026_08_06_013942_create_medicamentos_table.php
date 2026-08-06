<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('medicamentos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_generico', 150)->unique();
        $table->string('presentacion', 100)->comment('Ej. Ampolla, Comprimido, Solución');
        $table->string('concentracion', 100)->comment('Ej. 500mg, 1g, 50ml');
        $table->enum('status', ['Activo', 'Inactivo'])->default('Activo');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};
