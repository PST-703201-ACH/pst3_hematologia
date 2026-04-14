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
        Schema::table('municipio', function (Blueprint $table) {
            $table->foreign(['estado_id'], 'municipio_estado_id_fkey')->references(['estado_id'])->on('estado')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('municipio', function (Blueprint $table) {
            $table->dropForeign('municipio_estado_id_fkey');
        });
    }
};
