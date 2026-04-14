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
        Schema::table('protocolo_sesion', function (Blueprint $table) {
            $table->foreign(['ciclo_id'], 'protocolo_sesion_ciclo_id_fkey')->references(['ciclo_id'])->on('protocolo_ciclo')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('protocolo_sesion', function (Blueprint $table) {
            $table->dropForeign('protocolo_sesion_ciclo_id_fkey');
        });
    }
};
