<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Creamos primero la tabla de personas
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('cedula', 20)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['M', 'F']);
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->nullable()->unique();
            $table->text('direccion_detalle')->nullable();
            
            // Relación con Geografía
            $table->foreignId('parroquia_id')->nullable()->constrained('parroquias')->onDelete('set null');
            
            $table->timestamps();
        });

        // 2. Ahora que 'roles', 'users' y 'personas' existen, creamos la tabla intermedia de usuarios del sistema
        Schema::create('usuarios_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios_roles');
        Schema::dropIfExists('personas');
    }
};