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
        Schema::table('parroquia', function (Blueprint $table) {
            $table->foreign(['municipio_id'], 'parroquia_municipio_id_fkey')->references(['municipio_id'])->on('municipio')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parroquia', function (Blueprint $table) {
            $table->dropForeign('parroquia_municipio_id_fkey');
        });
    }
};
