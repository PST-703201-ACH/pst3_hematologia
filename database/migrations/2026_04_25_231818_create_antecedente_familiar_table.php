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
        Schema::create('antecedente_familiar', function (Blueprint $table) {
            $table->integer('ant_fam_id')->primary();
            $table->integer('paciente_id')->nullable();
            $table->integer('consulta_id')->nullable();
            $table->string('parentesco', 50)->nullable();
            $table->string('patologia', 150)->nullable();
            $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedente_familiar');
    }
};
