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
            $table->unsignedBigInteger('redis_id');
            $table->unsignedBigInteger('estados_id');
            $table->unsignedBigInteger('municipios_id');
            $table->string('parroquia');
            $table->string('localidad');
            $table->integer('cantidad_cc')->nullable();
            $table->unsignedBigInteger('tipos_obpp_id');
            $table->string('situr_obpp')->nullable();
            $table->string('nombre_obpp');
            $table->unsignedBigInteger('tipos_poblacion_id');
            $table->unsignedBigInteger('areas_items_id');
            $table->unsignedBigInteger('areas_procesos_id');
            $table->integer('cantidad_familias')->nullable();
            $table->integer('cantidad_asistentes')->nullable();
            $table->string('vocero_nombre')->nullable();
            $table->string('vocero_telefono')->nullable();
            $table->unsignedBigInteger('gestion_humana_id');
            $table->text('observacion')->nullable();
            $table->unsignedBigInteger('comunas_id')->nullable();
            $table->unsignedBigInteger('consejos_comunales_id')->nullable();

            $table->foreign('redis_id')->references('id')->on('redis')->cascadeOnDelete();
            $table->foreign('estados_id')->references('id')->on('estados')->cascadeOnDelete();
            $table->foreign('municipios_id')->references('id')->on('municipios')->cascadeOnDelete();
            $table->foreign('tipos_obpp_id')->references('id')->on('tipos_obpp')->cascadeOnDelete();
            $table->foreign('tipos_poblacion_id')->references('id')->on('tipos_poblacion')->cascadeOnDelete();
            $table->foreign('areas_items_id')->references('id')->on('areas_items')->cascadeOnDelete();
            $table->foreign('areas_procesos_id')->references('id')->on('areas_procesos')->cascadeOnDelete();
            $table->foreign('gestion_humana_id')->references('id')->on('gestion_humana')->cascadeOnDelete();
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
