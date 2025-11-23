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

        $fortalecimiento = [
            "UNIDAD DE PRODUCCION FAMILIAR",
            "EMPRESA DE PROPIEDAD SOCIAL DIRECTA COMUNAL",
            "EMPRESA DE PROPIEDAD SOCIAL INDIRECTA COMUNAL",
            "EMPRESA DE PRODUCCION SOCIAL MIXTA",
            "GRUPOS DE INTERCAMBIO SOLIDARIO",
            "COOPERATIVAS",
        ];

        foreach ($tiposObpp as $tipo){
            $is_fortalecimiento = in_array($tipo, $fortalecimiento) ? 1 : 0;
            TipoObpp::create([
                'nombre' => $tipo,
                'fortalecimiento' => $is_fortalecimiento
            ]);
        }


    }
}
