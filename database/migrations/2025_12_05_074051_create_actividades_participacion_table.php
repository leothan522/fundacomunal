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
        Schema::create('actividades_participacion', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->integer('cantidad_cc')->nullable();
            $table->string('situr_obpp');
            $table->string('nombre_obpp');
            $table->unsignedBigInteger('tipos_obpp_id');
            $table->unsignedBigInteger('redis_id');
            $table->unsignedBigInteger('estados_id');
            $table->unsignedBigInteger('municipios_id');
            $table->string('parroquia');
            $table->string('localidad');
            $table->unsignedBigInteger('comunas_id')->nullable();
            $table->unsignedBigInteger('consejos_comunales_id')->nullable();
            $table->foreign('tipos_obpp_id')->references('id')->on('tipos_obpp')->cascadeOnDelete();
            $table->foreign('redis_id')->references('id')->on('redis')->cascadeOnDelete();
            $table->foreign('estados_id')->references('id')->on('estados')->cascadeOnDelete();
            $table->foreign('municipios_id')->references('id')->on('municipios')->cascadeOnDelete();
            $table->foreign('comunas_id')->references('id')->on('comunas')->nullOnDelete();
            $table->foreign('consejos_comunales_id')->references('id')->on('consejos_comunales')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades_participacion');
    }
};
