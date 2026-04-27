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
<<<<<<<< HEAD:database/migrations/2026_04_25_231818_create_cache_locks_table.php
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration')->index();
========
        Schema::create('enfermedad', function (Blueprint $table) {
            $table->id('enfermedad_id')->primary();
            $table->boolean("tipo")->index();
            $table->string('nombre_enfermedad', 255)->unique();
>>>>>>>> c7102ae982eb025ac1f724ed72dd10a8616b4858:database/migrations/2026_04_14_003049_create_enfermedad_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:database/migrations/2026_04_25_231818_create_cache_locks_table.php
        Schema::dropIfExists('cache_locks');
========
        Schema::dropIfExists('enfermedad');
>>>>>>>> c7102ae982eb025ac1f724ed72dd10a8616b4858:database/migrations/2026_04_14_003049_create_enfermedad_table.php
    }
};
