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
        Schema::create('gestion_humana_vacaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gestion_humana_id');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('dias');
            $table->string('periodo')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreign('gestion_humana_id')->references('id')->on('gestion_humana')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestion_humana_vacaciones');
    }
};
