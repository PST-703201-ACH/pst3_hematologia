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
        Schema::create('protocolo_sesion', function (Blueprint $table) {
            $table->integer('sesion_id')->primary();
            $table->integer('ciclo_id')->nullable();
            $table->timestamp('fecha_hora')->nullable();
            $table->string('tipo_sesion', 50)->nullable();
            $table->string('status', 20)->nullable()->default('Programada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocolo_sesion');
    }
};
