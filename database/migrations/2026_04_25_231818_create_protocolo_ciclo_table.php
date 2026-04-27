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
        Schema::create('protocolo_ciclo', function (Blueprint $table) {
            $table->integer('ciclo_id')->primary();
            $table->integer('protocolo_id')->nullable();
            $table->integer('numero_ciclo')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->string('estado', 20)->nullable()->default('Pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocolo_ciclo');
    }
};
