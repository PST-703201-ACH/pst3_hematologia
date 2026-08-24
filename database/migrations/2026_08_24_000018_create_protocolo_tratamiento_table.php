<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('protocolo_tratamiento', function (Blueprint $table) {
            $table->increments('protocolo_id');
            $table->unsignedInteger('paciente_id')->nullable();
            $table->unsignedInteger('consulta_id')->nullable();
            $table->string('nombre', 100)->nullable();
            $table->text('indicaciones')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->string('estado', 20)->nullable()->default('Activo');
            $table->unsignedInteger('usuario_medico_id')->nullable();
            $table->foreign('paciente_id')->references('paciente_id')->on('paciente');
            $table->foreign('consulta_id')->references('consulta_id')->on('consulta');
            $table->foreign('usuario_medico_id')->references('usuario_id')->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('protocolo_tratamiento');
    }
};
