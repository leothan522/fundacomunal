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
        Schema::create('actividades_formacion', function (Blueprint $table) {
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

            $table->unsignedBigInteger('estrategias_formacion_id');
            $table->unsignedBigInteger('modalidades_formacion_id');
            $table->integer('cantidad_mujeres')->nullable();
            $table->integer('cantidad_hombres')->nullable();
            $table->unsignedBigInteger('medios_verificacion_id');

            $table->string('vocero_nombre')->nullable();
            $table->string('vocero_telefono')->nullable();
            $table->unsignedBigInteger('gestion_humana_id');
            $table->text('observacion')->nullable();

            $table->unsignedBigInteger('comunas_id')->nullable();
            $table->unsignedBigInteger('consejos_comunales_id')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->boolean('estatus')->nullable();

            $table->foreign('redis_id')->references('id')->on('redis')->cascadeOnDelete();
            $table->foreign('estados_id')->references('id')->on('estados')->cascadeOnDelete();
            $table->foreign('municipios_id')->references('id')->on('municipios')->cascadeOnDelete();
            $table->foreign('tipos_obpp_id')->references('id')->on('tipos_obpp')->cascadeOnDelete();
            $table->foreign('tipos_poblacion_id')->references('id')->on('tipos_poblacion')->cascadeOnDelete();
            $table->foreign('areas_items_id')->references('id')->on('areas_items')->cascadeOnDelete();
            $table->foreign('areas_procesos_id')->references('id')->on('areas_procesos')->cascadeOnDelete();

            $table->foreign('estrategias_formacion_id')->references('id')->on('estrategias_formacion')->cascadeOnDelete();
            $table->foreign('modalidades_formacion_id')->references('id')->on('modalidades_formacion')->cascadeOnDelete();
            $table->foreign('medios_verificacion_id')->references('id')->on('medios_verificacion')->cascadeOnDelete();


            $table->foreign('gestion_humana_id')->references('id')->on('gestion_humana')->cascadeOnDelete();
            $table->foreign('comunas_id')->references('id')->on('comunas')->nullOnDelete();
            $table->foreign('consejos_comunales_id')->references('id')->on('consejos_comunales')->nullOnDelete();
            $table->foreign('users_id')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades_formacion');
    }
};
