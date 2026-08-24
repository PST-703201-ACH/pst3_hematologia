<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examen', function (Blueprint $table) {
            $table->increments('examen_id');
            $table->string('nombre', 100)->nullable()->unique();
            $table->string('unidad_medida', 20)->nullable();
            $table->double('valor_ref_min')->nullable();
            $table->double('valor_ref_max')->nullable();
            $table->string('status', 15)->nullable()->default('Activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examen');
    }
};
