<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frotis_fsp', function (Blueprint $table) {
            $table->increments('fsp_id');
            $table->unsignedInteger('consulta_id')->nullable();
            $table->double('seg_val')->nullable();
            $table->double('lin_val')->nullable();
            $table->double('mon_val')->nullable();
            $table->double('eos_val')->nullable();
            $table->double('blastos_val')->nullable();
            $table->text('morfologia')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreign('consulta_id')->references('consulta_id')->on('consulta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frotis_fsp');
    }
};
