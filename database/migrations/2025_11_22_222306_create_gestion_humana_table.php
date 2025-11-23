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
        Schema::create('gestion_humana', function (Blueprint $table) {
            $table->id();
            $table->integer('cedula');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('tipos_personal_id')->nullable();;
            $table->unsignedBigInteger('categorias_id')->nullable();
            $table->string('ente')->nullable();
            $table->unsignedBigInteger('redis_id')->nullable();
            $table->unsignedBigInteger('estados_id')->nullable();
            $table->unsignedBigInteger('municipios_id')->nullable();
            $table->string('parroquia')->nullable();
            $table->text('observacion')->nullable();
            $table->date('fecha')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->foreign('tipos_personal_id')->references('id')->on('tipos_personal')->nullOnDelete();
            $table->foreign('categorias_id')->references('id')->on('categorias')->nullOnDelete();
            $table->foreign('redis_id')->references('id')->on('redis')->nullOnDelete();
            $table->foreign('estados_id')->references('id')->on('estados')->nullOnDelete();
            $table->foreign('municipios_id')->references('id')->on('municipios')->nullOnDelete();
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
        Schema::dropIfExists('gestion_humana');
    }
};
