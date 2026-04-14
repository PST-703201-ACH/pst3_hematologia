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
        Schema::table('protocolo_tratamiento', function (Blueprint $table) {
            $table->foreign(['consulta_id'], 'protocolo_tratamiento_consulta_id_fkey')->references(['consulta_id'])->on('consulta')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['paciente_id'], 'protocolo_tratamiento_paciente_id_fkey')->references(['paciente_id'])->on('paciente')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['usuario_medico_id'], 'protocolo_tratamiento_usuario_medico_id_fkey')->references(['usuario_id'])->on('usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('protocolo_tratamiento', function (Blueprint $table) {
            $table->dropForeign('protocolo_tratamiento_consulta_id_fkey');
            $table->dropForeign('protocolo_tratamiento_paciente_id_fkey');
            $table->dropForeign('protocolo_tratamiento_usuario_medico_id_fkey');
        });
    }
};
