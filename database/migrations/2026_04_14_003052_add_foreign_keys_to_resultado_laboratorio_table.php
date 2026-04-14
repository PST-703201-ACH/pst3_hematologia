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
        Schema::table('resultado_laboratorio', function (Blueprint $table) {
            $table->foreign(['examen_id'], 'resultado_laboratorio_examen_id_fkey')->references(['examen_id'])->on('examen_catalogo')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['orden_id'], 'resultado_laboratorio_orden_id_fkey')->references(['orden_id'])->on('orden_laboratorio')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resultado_laboratorio', function (Blueprint $table) {
            $table->dropForeign('resultado_laboratorio_examen_id_fkey');
            $table->dropForeign('resultado_laboratorio_orden_id_fkey');
        });
    }
};
