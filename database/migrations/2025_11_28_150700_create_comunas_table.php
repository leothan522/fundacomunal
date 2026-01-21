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
        Schema::create('comunas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cod_com');
            $table->string('cod_situr');
            $table->integer('cantidad_cc')->default(0);
            $table->string('tipo_obpp')->nullable();
            $table->unsignedBigInteger('redis_id')->nullable();
            $table->unsignedBigInteger('estados_id')->nullable();
            $table->unsignedBigInteger('municipios_id')->nullable();
            $table->string('parroquia')->nullable();
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
        Schema::dropIfExists('comunas');
    }
};
