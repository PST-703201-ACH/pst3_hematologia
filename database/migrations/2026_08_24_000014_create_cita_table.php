<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cita', function (Blueprint $table) {
            $table->increments('cita_id');
            $table->string('nombres_paciente', 40)->nullable();
            $table->string('apellidos_paciente', 40)->nullable();
            $table->string('nombres_representante', 40)->nullable();
            $table->string('apellidos_representante', 40)->nullable();
            $table->integer('numero_hc')->nullable();
            $table->timestamp('fecha_hora')->nullable();
            $table->unsignedInteger('consulta_id')->nullable();
            $table->foreign('consulta_id')->references('consulta_id')->on('consulta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cita');
    }
};
