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
        Schema::create('persona', function (Blueprint $table) {
            $table->integer('persona_id')->primary();
            $table->string('nombres', 100)->nullable();
            $table->string('apellidos', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo', 12)->nullable();
            $table->string('cedula', 15)->nullable()->unique('persona_cedula_key');
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('estado_id')->nullable();
            $table->integer('municipio_id')->nullable();
            $table->integer('parroquia_id')->nullable();
            $table->text('direccion_exacta')->nullable();
            $table->string('status', 20)->nullable()->default('Activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
