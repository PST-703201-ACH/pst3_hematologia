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
        Schema::create('resultado_laboratorio', function (Blueprint $table) {
            $table->integer('resultado_id')->primary();
            $table->integer('orden_id')->nullable();
            $table->integer('examen_id')->nullable();
            $table->float('valor_encontrado')->nullable();
            $table->string('status', 20)->nullable()->default('Activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultado_laboratorio');
    }
};
