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
        Schema::table('consulta', function (Blueprint $table) {
            $table->foreign(['cie10_id'], 'consulta_cie10_id_fkey')->references(['enfermedad_id'])->on('cie10')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['paciente_id'], 'consulta_paciente_id_fkey')->references(['paciente_id'])->on('paciente')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['tipo_id'], 'consulta_tipo_id_fkey')->references(['tipo_id'])->on('tipo_consulta_catalogo')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['usuario_medico_id'], 'consulta_usuario_medico_id_fkey')->references(['usuario_id'])->on('usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consulta', function (Blueprint $table) {
            $table->dropForeign('consulta_cie10_id_fkey');
            $table->dropForeign('consulta_paciente_id_fkey');
            $table->dropForeign('consulta_tipo_id_fkey');
            $table->dropForeign('consulta_usuario_medico_id_fkey');
        });
    }
};
