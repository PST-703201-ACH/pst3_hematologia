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
        Schema::create('enfermedad', function (Blueprint $table) {
            $table->bigIncrements('enfermedad_id');
            $table->boolean('tipo')->index();
            $table->string('nombre_enfermedad')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enfermedad');
    }
};
