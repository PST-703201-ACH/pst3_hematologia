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
        Schema::table('paciente_representante', function (Blueprint $table) {
            $table->foreign(['paciente_id'], 'paciente_representante_paciente_id_fkey')->references(['paciente_id'])->on('paciente')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['representante_id'], 'paciente_representante_representante_id_fkey')->references(['representante_id'])->on('representante')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paciente_representante', function (Blueprint $table) {
            $table->dropForeign('paciente_representante_paciente_id_fkey');
            $table->dropForeign('paciente_representante_representante_id_fkey');
        });
    }
};
