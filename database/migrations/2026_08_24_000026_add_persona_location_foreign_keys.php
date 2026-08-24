<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            $table->foreign('estado_id')->references('estado_id')->on('estado');
            $table->foreign('municipio_id')->references('municipio_id')->on('municipio');
            $table->foreign('parroquia_id')->references('parroquia_id')->on('parroquia');
        });
    }

    public function down(): void
    {
        Schema::table('persona', function (Blueprint $table) {
            $table->dropForeign(['estado_id']);
            $table->dropForeign(['municipio_id']);
            $table->dropForeign(['parroquia_id']);
        });
    }
};
