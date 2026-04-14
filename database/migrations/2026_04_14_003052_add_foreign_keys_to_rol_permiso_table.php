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
        Schema::table('rol_permiso', function (Blueprint $table) {
            $table->foreign(['permiso_id'], 'rol_permiso_permiso_id_fkey')->references(['permiso_id'])->on('permiso')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['rol_id'], 'rol_permiso_rol_id_fkey')->references(['rol_id'])->on('rol')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rol_permiso', function (Blueprint $table) {
            $table->dropForeign('rol_permiso_permiso_id_fkey');
            $table->dropForeign('rol_permiso_rol_id_fkey');
        });
    }
};
