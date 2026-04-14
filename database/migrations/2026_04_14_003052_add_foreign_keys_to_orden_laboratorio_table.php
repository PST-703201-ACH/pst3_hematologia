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
        Schema::table('orden_laboratorio', function (Blueprint $table) {
            $table->foreign(['consulta_id'], 'orden_laboratorio_consulta_id_fkey')->references(['consulta_id'])->on('consulta')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orden_laboratorio', function (Blueprint $table) {
            $table->dropForeign('orden_laboratorio_consulta_id_fkey');
        });
    }
};
