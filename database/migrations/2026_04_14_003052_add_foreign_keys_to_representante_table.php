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
        Schema::table('representante', function (Blueprint $table) {
            $table->foreign(['persona_id'], 'representante_persona_id_fkey')->references(['persona_id'])->on('persona')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('representante', function (Blueprint $table) {
            $table->dropForeign('representante_persona_id_fkey');
        });
    }
};
