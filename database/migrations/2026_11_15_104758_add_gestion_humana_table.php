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
            $table->string('profesion')->nullable()->after('fecha_ingreso');
            $table->string('postgrado')->nullable()->after('profesion');
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
