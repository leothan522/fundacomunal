<?php

namespace Database\Seeders;

use App\Models\TipoEconomica;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEconomicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposEconomica = [
            "SERVICIOS",
            "SOCIAL",
            "PRODUCTIVA"
        ];
        foreach ($tiposEconomica as $tipo){
            TipoEconomica::create(['nombre' => $tipo]);
        }
    }
}
