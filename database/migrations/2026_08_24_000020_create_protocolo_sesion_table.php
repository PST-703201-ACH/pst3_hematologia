<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('protocolo_sesion', function (Blueprint $table) {
            $table->increments('sesion_id');
            $table->unsignedInteger('ciclo_id')->nullable();
            $table->timestamp('fecha_hora')->nullable();
            $table->string('tipo_sesion', 50)->nullable();
            $table->string('status', 20)->nullable()->default('Programada');
            $table->foreign('ciclo_id')->references('ciclo_id')->on('protocolo_ciclo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('protocolo_sesion');
    }
};
