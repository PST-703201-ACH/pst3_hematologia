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
        Schema::create('tipo_consulta_catalogo', function (Blueprint $table) {
            $table->integer('tipo_id')->primary();
            $table->string('nombre', 50)->nullable()->unique('tipo_consulta_catalogo_nombre_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_consulta_catalogo');
    }
};
