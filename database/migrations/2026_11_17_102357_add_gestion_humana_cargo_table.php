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
        Schema::table('gestion_humana', function (Blueprint $table) {
            $table->bigInteger('cargos_id')->unsigned()->nullable()->after('fecha_ingreso');
            $table->string('sexo')->nullable()->after('postgrado');
            $table->foreign('cargos_id')->references('id')->on('gestion_humana_cargos')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gestion_humana', function (Blueprint $table) {
            //
        });
    }
};
