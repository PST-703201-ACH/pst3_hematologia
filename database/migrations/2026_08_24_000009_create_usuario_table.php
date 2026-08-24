<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments('usuario_id');
            $table->unsignedInteger('persona_id')->nullable();
            $table->string('username', 50)->nullable()->unique();
            $table->string('password_hash', 255)->nullable();
            $table->integer('status')->nullable();
            $table->unsignedInteger('id_rol')->nullable();
            $table->foreign('persona_id')->references('persona_id')->on('persona');
            $table->foreign('id_rol')->references('rol_id')->on('rol');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
