<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enfermedad', function (Blueprint $table) {
            $table->increments('enfermedad_id');
            $table->boolean('tipo')->nullable();
            $table->string('descripcion', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enfermedad');
    }
};
