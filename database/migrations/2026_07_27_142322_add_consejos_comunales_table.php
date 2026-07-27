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
        Schema::table('consejos_comunales', function (Blueprint $table) {
            $table->boolean('is_eleccion')->default(false)->after('parroquia');
            $table->date('fecha_eleccion')->nullable()->after('is_eleccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consejos_comunales', function (Blueprint $table) {
            //
        });
    }
};
