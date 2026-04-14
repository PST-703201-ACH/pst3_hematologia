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
        Schema::create('protocolo_tratamiento', function (Blueprint $table) {
            $table->integer('protocolo_id')->primary();
            $table->integer('paciente_id')->nullable();
            $table->integer('consulta_id')->nullable();
            $table->string('nombre', 100)->nullable();
            $table->text('indicaciones')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->string('estado', 20)->nullable()->default('Activo');
            $table->integer('usuario_medico_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocolo_tratamiento');
    }
};
