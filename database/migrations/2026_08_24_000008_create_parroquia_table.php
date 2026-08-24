<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parroquia', function (Blueprint $table) {
            $table->increments('parroquia_id');
            $table->unsignedInteger('municipio_id')->nullable();
            $table->string('nombre', 40);
            $table->foreign('municipio_id')->references('municipio_id')->on('municipio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parroquia');
    }
};
