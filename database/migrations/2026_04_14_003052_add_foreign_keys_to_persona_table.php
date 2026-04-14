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
        Schema::table('persona', function (Blueprint $table) {
            $table->foreign(['estado_id'], 'persona_estado_id_fkey')->references(['estado_id'])->on('estado')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['municipio_id'], 'persona_municipio_id_fkey')->references(['municipio_id'])->on('municipio')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['parroquia_id'], 'persona_parroquia_id_fkey')->references(['parroquia_id'])->on('parroquia')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            $table->dropForeign('persona_estado_id_fkey');
            $table->dropForeign('persona_municipio_id_fkey');
            $table->dropForeign('persona_parroquia_id_fkey');
        });
    }
};
