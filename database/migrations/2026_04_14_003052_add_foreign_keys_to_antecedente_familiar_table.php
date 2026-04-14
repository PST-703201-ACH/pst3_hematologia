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
        Schema::table('antecedente_familiar', function (Blueprint $table) {
            $table->foreign(['consulta_id'], 'antecedente_familiar_consulta_id_fkey')->references(['consulta_id'])->on('consulta')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['paciente_id'], 'antecedente_familiar_paciente_id_fkey')->references(['paciente_id'])->on('paciente')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antecedente_familiar', function (Blueprint $table) {
            $table->dropForeign('antecedente_familiar_consulta_id_fkey');
            $table->dropForeign('antecedente_familiar_paciente_id_fkey');
        });
    }
};
