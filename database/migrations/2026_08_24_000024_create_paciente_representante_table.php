<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paciente_representante', function (Blueprint $table) {
            $table->unsignedInteger('paciente_id');
            $table->unsignedInteger('representante_id');
            $table->string('parentesco', 50)->nullable();
            $table->boolean('es_principal')->nullable()->default(true);
            $table->primary(['paciente_id', 'representante_id']);
            $table->foreign('paciente_id')->references('paciente_id')->on('paciente');
            $table->foreign('representante_id')->references('representante_id')->on('representante');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paciente_representante');
    }
};
