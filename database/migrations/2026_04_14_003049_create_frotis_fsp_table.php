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
        Schema::create('frotis_fsp', function (Blueprint $table) {
            $table->integer('fsp_id')->primary();
            $table->integer('consulta_id')->nullable();
            $table->float('seg_val')->nullable();
            $table->float('lin_val')->nullable();
            $table->float('mon_val')->nullable();
            $table->float('eos_val')->nullable();
            $table->float('blastos_val')->nullable();
            $table->text('morfologia')->nullable();
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frotis_fsp');
    }
};
