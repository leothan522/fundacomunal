<?php

namespace Database\Seeders;

use App\Models\TipoObpp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoObppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposObpp = [
            "CONSEJO COMUNAL",
            "UNIDAD DE PRODUCCION FAMILIAR",
            "EMPRESA DE PROPIEDAD SOCIAL DIRECTA COMUNAL",
            "EMPRESA DE PROPIEDAD SOCIAL INDIRECTA COMUNAL",
            "EMPRESA DE PRODUCCION SOCIAL MIXTA",
            "GRUPOS DE INTERCAMBIO SOLIDARIO",
            "COOPERATIVAS",
            "COMUNA",
            "CIRCUITO"
        ];

        foreach ($tiposObpp as $tipo){
            TipoObpp::create(['nombre' => $tipo]);
        }
    }
}
