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
        Schema::table('transfusion', function (Blueprint $table) {
            $table->foreign(['consulta_id'], 'transfusion_consulta_id_fkey')->references(['consulta_id'])->on('consulta')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['paciente_id'], 'transfusion_paciente_id_fkey')->references(['paciente_id'])->on('paciente')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['unidad_id'], 'transfusion_unidad_id_fkey')->references(['unidad_id'])->on('unidad_hemocomponente')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfusion', function (Blueprint $table) {
            $table->dropForeign('transfusion_consulta_id_fkey');
            $table->dropForeign('transfusion_paciente_id_fkey');
            $table->dropForeign('transfusion_unidad_id_fkey');
        });
    }
};
