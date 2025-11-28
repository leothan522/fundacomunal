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
        Schema::create('consejos_comunales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('situr_viejo')->nullable();
            $table->string('situr_nuevo')->nullable();
            $table->string('tipo');
            $table->unsignedBigInteger('comunas_id')->nullable();
            $table->unsignedBigInteger('redis_id')->nullable();
            $table->unsignedBigInteger('estados_id')->nullable();
            $table->unsignedBigInteger('municipios_id')->nullable();
            $table->string('parroquia')->nullable();
            $table->foreign('comunas_id')->references('id')->on('comunas')->nullOnDelete();
            $table->foreign('redis_id')->references('id')->on('redis')->nullOnDelete();
            $table->foreign('estados_id')->references('id')->on('estados')->nullOnDelete();
            $table->foreign('municipios_id')->references('id')->on('municipios')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consejos_comunales');
    }
};
