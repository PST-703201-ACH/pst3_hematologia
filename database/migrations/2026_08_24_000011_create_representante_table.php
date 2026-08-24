<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('representante', function (Blueprint $table) {
            $table->increments('representante_id');
            $table->unsignedInteger('persona_id')->nullable();
            $table->string('ocupacion', 100)->nullable();
            $table->foreign('persona_id')->references('persona_id')->on('persona');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('representante');
    }
};
