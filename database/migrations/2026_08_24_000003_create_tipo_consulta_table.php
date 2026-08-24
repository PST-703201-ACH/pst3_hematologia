<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_consulta', function (Blueprint $table) {
            $table->increments('tipo_id');
            $table->string('nombre', 50)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_consulta');
    }
};
