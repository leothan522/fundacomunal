<?php

namespace Database\Seeders;

use App\Models\EstrategiaFormacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstrategiaFormacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estrategiasFormacion = [
            "TALLER",
            "CHARLA",
            "CONVERSATORIO",
            "CAPACITACION",
            "MESA DE TRABAJO",
            "FORO",
            "SEMINARIO",
            "INDUCCION",
            "VIDEO CONFERENCIA"
        ];
        foreach ($estrategiasFormacion as $estrategia){
            EstrategiaFormacion::create(['nombre' => $estrategia]);
        }
    }
}
