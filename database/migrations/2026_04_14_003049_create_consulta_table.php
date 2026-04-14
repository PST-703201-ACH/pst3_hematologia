<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consulta', function (Blueprint $table) {
            $table->integer('consulta_id')->primary();
            $table->integer('paciente_id')->nullable();
            $table->integer('usuario_medico_id')->nullable();
            $table->integer('tipo_id')->nullable();
            $table->integer('cie10_id')->nullable();
            $table->timestamp('fecha_hora')->nullable()->default(DB::raw("now()"));
            $table->text('motivo_consulta')->nullable();
            $table->float('peso')->nullable();
            $table->float('talla')->nullable();
            $table->float('sc')->nullable();
            $table->integer('fc')->nullable();
            $table->integer('fr')->nullable();
            $table->text('subjetivo')->nullable();
            $table->text('plan_trabajo')->nullable();
            $table->date('proxima_cita')->nullable();
            $table->string('status', 20)->nullable()->default('Abierta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulta');
    }
};
