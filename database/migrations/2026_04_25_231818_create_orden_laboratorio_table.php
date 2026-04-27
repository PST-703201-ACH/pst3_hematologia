<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orden_laboratorio', function (Blueprint $table) {
            $table->integer('orden_id')->primary();
            $table->integer('consulta_id')->nullable();
            $table->string('tipo_seguimiento', 100)->nullable();
            $table->timestamp('fecha_solicitud')->nullable()->default(DB::raw("now()"));
            $table->string('status', 20)->nullable()->default('Pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_laboratorio');
    }
};
