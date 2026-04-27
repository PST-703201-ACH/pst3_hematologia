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
        Schema::create('tipaje_sanguineo', function (Blueprint $table) {
            $table->integer('tipaje_id')->primary();
            $table->integer('paciente_id')->nullable();
            $table->date('fecha')->nullable();
            $table->string('grupo_ab', 3)->nullable();
            $table->string('factor_rh', 3)->nullable();
            $table->text('fenotipo_extendido')->nullable();
            $table->text('genetica_transfusional')->nullable();
            $table->string('metodo', 50)->nullable();
            $table->integer('usuario_registrador_id')->nullable();
            $table->string('status', 20)->nullable()->default('Activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipaje_sanguineo');
    }
};
