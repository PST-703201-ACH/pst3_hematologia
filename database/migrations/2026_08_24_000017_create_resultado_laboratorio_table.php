<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultado_laboratorio', function (Blueprint $table) {
            $table->increments('resultado_id');
            $table->unsignedInteger('orden_id')->nullable();
            $table->unsignedInteger('examen_id')->nullable();
            $table->double('valor_encontrado')->nullable();
            $table->string('status', 20)->nullable()->default('Activo');
            $table->foreign('orden_id')->references('orden_id')->on('orden_laboratorio');
            $table->foreign('examen_id')->references('examen_id')->on('examen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultado_laboratorio');
    }
};
