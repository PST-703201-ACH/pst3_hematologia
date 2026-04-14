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
        Schema::create('cie10', function (Blueprint $table) {
            $table->integer('enfermedad_id')->primary();
            $table->string('codigo', 10)->nullable()->unique('cie10_codigo_key');
            $table->string('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cie10');
    }
};
