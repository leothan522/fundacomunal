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
        Schema::table('actividades_participacion', function (Blueprint $table) {
            $table->unsignedBigInteger('medios_verificacion_id')->nullable()->after('cantidad_asistentes');
            $table->foreign('medios_verificacion_id')->references('id')->on('medios_verificacion')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividades_participacion', function (Blueprint $table) {
            //
        });
    }
};
