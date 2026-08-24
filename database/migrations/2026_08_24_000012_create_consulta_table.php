<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consulta', function (Blueprint $table) {
            $table->increments('consulta_id');
            $table->unsignedInteger('paciente_id')->nullable();
            $table->unsignedInteger('medico_id')->nullable();
            $table->unsignedInteger('tipo_id')->nullable();
            $table->unsignedInteger('enfermedad_id')->nullable();
            $table->timestamp('fecha_hora')->nullable()->useCurrent();
            $table->double('peso')->nullable();
            $table->double('talla')->nullable();
            $table->double('sc')->nullable();
            $table->double('fc')->nullable();
            $table->double('fr')->nullable();
            $table->text('subjetivo')->nullable();
            $table->text('plan_trabajo')->nullable();
            $table->date('proxima_cita')->nullable();
            $table->integer('status')->nullable();
            $table->foreign('paciente_id')->references('paciente_id')->on('paciente');
            $table->foreign('medico_id')->references('usuario_id')->on('usuario');
            $table->foreign('tipo_id')->references('tipo_id')->on('tipo_consulta');
            $table->foreign('enfermedad_id')->references('enfermedad_id')->on('enfermedad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consulta');
    }
};
