<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidad_hemocomponente', function (Blueprint $table) {
            $table->increments('unidad_id');
            $table->string('codigo_bolsa', 20)->nullable()->unique();
            $table->string('tipo_componente', 100)->nullable();
            $table->string('grupo_rh', 5)->nullable();
            $table->double('volumen_ml')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('status', 20)->nullable()->default('Disponible');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidad_hemocomponente');
    }
};
