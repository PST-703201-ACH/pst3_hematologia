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
    Schema::create('medicamento_protocolo_sesion', function (Blueprint $table) {
        $table->id();
        
        // Llaves foráneas seguras
        $table->foreignId('sesion_id')->constrained('protocolo_sesion', 'sesion_id')->onDelete('cascade');
        $table->foreignId('medicamento_id')->constrained('medicamentos')->onDelete('cascade');
        
        // Datos específicos de la aplicación
        $table->string('dosis_indicada', 100)->comment('Cantidad exacta para esta sesión');
        $table->string('via_administracion', 50)->comment('Ej. Intravenosa, Vía Oral');
        $table->text('observaciones_aplicacion')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamento_protocolo_sesion');
    }
};
