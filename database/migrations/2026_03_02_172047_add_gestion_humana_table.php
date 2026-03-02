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
            $table->text('image_path')->nullable()->after('fecha_ingreso');
            $table->date('image_fecha')->nullable()->after('image_path');
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
