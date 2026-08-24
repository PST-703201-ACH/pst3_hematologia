<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_laboratorio', function (Blueprint $table) {
            $table->increments('orden_id');
            $table->unsignedInteger('consulta_id')->nullable();
            $table->string('tipo_seguimiento', 100)->nullable();
            $table->timestamp('fecha_solicitud')->nullable()->useCurrent();
            $table->string('status', 20)->nullable()->default('Pendiente');
            $table->foreign('consulta_id')->references('consulta_id')->on('consulta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_laboratorio');
    }
};
