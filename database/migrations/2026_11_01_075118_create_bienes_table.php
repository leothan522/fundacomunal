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
        Schema::create('bienes', function (Blueprint $table) {
            $table->id();
            $table->string('serial')->unique();

            $table->foreignId('bienes_ubicaciones_id')
                ->constrained('bienes_ubicaciones')
                ->restrictOnDelete();

            $table->foreignId('bienes_condiciones_id')
                ->constrained('bienes_condiciones')
                ->restrictOnDelete();

            $table->foreignId('bienes_tipos_id')
                ->nullable()
                ->constrained('bienes_tipos')
                ->nullOnDelete();

            $table->foreignId('bienes_marcas_id')
                ->nullable()
                ->constrained('bienes_marcas')
                ->nullOnDelete();

            $table->foreignId('bienes_modelos_id')
                ->nullable()
                ->constrained('bienes_modelos')
                ->nullOnDelete();

            $table->foreignId('bienes_colores_id')
                ->nullable()
                ->constrained('bienes_colores')
                ->nullOnDelete();

            $table->text('informacion_adicional')->nullable();
            $table->text('imagen_frontal')->nullable();
            $table->text('imagen_posterior')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bienes');
    }
};
