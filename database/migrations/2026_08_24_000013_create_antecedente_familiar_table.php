<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antecedente_familiar', function (Blueprint $table) {
            $table->increments('ant_fam_id');
            $table->unsignedInteger('paciente_id')->nullable();
            $table->unsignedInteger('consulta_id')->nullable();
            $table->string('parentesco', 50)->nullable();
            $table->string('patologia', 150)->nullable();
            $table->text('descripcion')->nullable();
            $table->foreign('paciente_id')->references('paciente_id')->on('paciente');
            $table->foreign('consulta_id')->references('consulta_id')->on('consulta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antecedente_familiar');
    }
};
