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
        Schema::create('examen_catalogo', function (Blueprint $table) {
            $table->integer('examen_id')->primary();
            $table->string('nombre', 100)->nullable()->unique('examen_catalogo_nombre_key');
            $table->string('unidad_medida', 20)->nullable();
            $table->float('valor_ref_min')->nullable();
            $table->float('valor_ref_max')->nullable();
            $table->string('status', 15)->nullable()->default('Activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_catalogo');
    }
};
