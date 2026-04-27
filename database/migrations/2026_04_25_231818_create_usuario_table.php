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
        Schema::create('usuario', function (Blueprint $table) {
            $table->integer('usuario_id')->primary();
            $table->integer('persona_id')->nullable();
            $table->string('username', 50)->nullable()->unique('usuario_username_key');
            $table->string('password_hash')->nullable();
            $table->integer('id_rol')->nullable();
            $table->string('status', 20)->nullable()->default('Activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
