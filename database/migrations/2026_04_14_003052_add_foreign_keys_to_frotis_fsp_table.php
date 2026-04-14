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
        Schema::table('frotis_fsp', function (Blueprint $table) {
            $table->foreign(['consulta_id'], 'frotis_fsp_consulta_id_fkey')->references(['consulta_id'])->on('consulta')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frotis_fsp', function (Blueprint $table) {
            $table->dropForeign('frotis_fsp_consulta_id_fkey');
        });
    }
};
