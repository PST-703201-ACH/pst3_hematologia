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
        Schema::create('paciente_representante', function (Blueprint $table) {
            $table->integer('paciente_id');
            $table->integer('representante_id');
            $table->string('parentesco', 50)->nullable();
            $table->boolean('es_principal')->nullable()->default(true);

            $table->primary(['paciente_id', 'representante_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paciente_representante');
    }
};
