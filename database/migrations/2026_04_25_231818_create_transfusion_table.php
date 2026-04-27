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
        Schema::create('transfusion', function (Blueprint $table) {
            $table->integer('transfusion_id')->primary();
            $table->integer('paciente_id')->nullable();
            $table->integer('unidad_id')->nullable();
            $table->integer('consulta_id')->nullable();
            $table->timestamp('fecha_hora')->nullable();
            $table->float('volumen_adm')->nullable();
            $table->string('status', 20)->nullable()->default('Realizada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfusion');
    }
};
