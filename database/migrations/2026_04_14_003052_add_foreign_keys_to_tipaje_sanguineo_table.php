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
        Schema::table('tipaje_sanguineo', function (Blueprint $table) {
            $table->foreign(['paciente_id'], 'tipaje_sanguineo_paciente_id_fkey')->references(['paciente_id'])->on('paciente')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['usuario_registrador_id'], 'tipaje_sanguineo_usuario_registrador_id_fkey')->references(['usuario_id'])->on('usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipaje_sanguineo', function (Blueprint $table) {
            $table->dropForeign('tipaje_sanguineo_paciente_id_fkey');
            $table->dropForeign('tipaje_sanguineo_usuario_registrador_id_fkey');
        });
    }
};
