<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfusion', function (Blueprint $table) {
            $table->increments('transfusion_id');
            $table->unsignedInteger('paciente_id')->nullable();
            $table->unsignedInteger('unidad_id')->nullable();
            $table->unsignedInteger('consulta_id')->nullable();
            $table->timestamp('fecha_hora')->nullable();
            $table->double('volumen_adm')->nullable();
            $table->string('status', 20)->nullable()->default('Realizada');
            $table->foreign('paciente_id')->references('paciente_id')->on('paciente');
            $table->foreign('unidad_id')->references('unidad_id')->on('unidad_hemocomponente');
            $table->foreign('consulta_id')->references('consulta_id')->on('consulta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfusion');
    }
};
