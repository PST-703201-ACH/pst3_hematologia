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
        Schema::table('protocolo_ciclo', function (Blueprint $table) {
            $table->foreign(['protocolo_id'], 'protocolo_ciclo_protocolo_id_fkey')->references(['protocolo_id'])->on('protocolo_tratamiento')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('protocolo_ciclo', function (Blueprint $table) {
            $table->dropForeign('protocolo_ciclo_protocolo_id_fkey');
        });
    }
};
