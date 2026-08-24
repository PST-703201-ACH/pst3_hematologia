<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('protocolo_ciclo', function (Blueprint $table) {
            $table->increments('ciclo_id');
            $table->unsignedInteger('protocolo_id')->nullable();
            $table->integer('numero_ciclo')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->string('estado', 20)->nullable()->default('Pendiente');
            $table->foreign('protocolo_id')->references('protocolo_id')->on('protocolo_tratamiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('protocolo_ciclo');
    }
};
