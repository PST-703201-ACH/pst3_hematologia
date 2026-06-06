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
        // 1. Tabla de Tipos de Consulta (Sucesiva, Primera vez, Emergencia, etc.)
        Schema::create('tipo_consulta', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 2. Tabla de Enfermedades / Diagnósticos Previos (Patologías de base como Anemia, Leucemia, etc.)
        Schema::create('enfermedad', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_cie10', 10)->nullable()->unique(); // Código médico internacional si se necesita
            $table->string('nombre', 150)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        // 3. Tabla de Catálogo de Exámenes (Hemoglobina, Cuenta Blanca, Plaquetas, Hematocrito, etc.)
        Schema::create('examen', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_examen', 20)->unique(); // Ej: HEM01, PLA02
            $table->string('nombre', 150)->unique();
            $table->string('unidad_medida', 20)->nullable(); // Ej: g/dL, %, mm3
            $table->decimal('valor_minimo_referencia', 8, 2)->nullable();
            $table->decimal('valor_maximo_referencia', 8, 2)->nullable();
            $table->text('indicaciones')->nullable(); // Ej: Ayuno obligatorio
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Se borran en orden inverso para evitar conflictos
        Schema::dropIfExists('examen');
        Schema::dropIfExists('enfermedad');
        Schema::dropIfExists('tipo_consulta');
    }
};