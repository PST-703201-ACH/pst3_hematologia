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
        Schema::table('usuario_permiso', function (Blueprint $table) {
            $table->foreign(['permiso_id'], 'usuario_permiso_permiso_id_fkey')->references(['permiso_id'])->on('permiso')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['usuario_id'], 'usuario_permiso_usuario_id_fkey')->references(['usuario_id'])->on('usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario_permiso', function (Blueprint $table) {
            $table->dropForeign('usuario_permiso_permiso_id_fkey');
            $table->dropForeign('usuario_permiso_usuario_id_fkey');
        });
    }
};
