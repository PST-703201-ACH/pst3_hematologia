<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paciente', function (Blueprint $table) {
            $table->increments('paciente_id');
            $table->unsignedInteger('persona_id')->nullable();
            $table->string('hc', 30)->nullable()->unique();
            $table->integer('status')->nullable();
            $table->foreign('persona_id')->references('persona_id')->on('persona');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paciente');
    }
};
