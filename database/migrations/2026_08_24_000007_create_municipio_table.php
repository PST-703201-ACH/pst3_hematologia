<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipio', function (Blueprint $table) {
            $table->increments('municipio_id');
            $table->unsignedInteger('estado_id')->nullable();
            $table->string('nombre', 50);
            $table->foreign('estado_id')->references('estado_id')->on('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipio');
    }
};
