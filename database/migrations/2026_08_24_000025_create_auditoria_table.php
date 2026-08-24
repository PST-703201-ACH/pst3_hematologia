<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria', function (Blueprint $table) {
            $table->increments('auditoria_id');
            $table->string('descripcion', 200)->nullable();
            $table->string('modulo', 50)->nullable();
            $table->unsignedInteger('id_usuario')->nullable();
            $table->timestamp('fecha_hora')->nullable()->useCurrent();
            $table->text('accion')->nullable();
            $table->foreign('id_usuario')->references('usuario_id')->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria');
    }
};
