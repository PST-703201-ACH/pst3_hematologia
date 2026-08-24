<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->increments('persona_id');
            $table->string('nombres', 100)->nullable();
            $table->string('apellidos', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo', 12)->nullable();
            $table->string('cedula', 15)->nullable()->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->unsignedInteger('estado_id')->nullable();
            $table->unsignedInteger('municipio_id')->nullable();
            $table->unsignedInteger('parroquia_id')->nullable();
            $table->text('direccion_exacta')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
