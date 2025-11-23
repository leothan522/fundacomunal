<?php

namespace Database\Seeders;

use App\Models\Etapa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EtapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $etapas = [
            "INICIO",
            "EN PROCESO",
            "CULMINADO",
            "PARALIZADO"
        ];
        foreach ($etapas as $etapa){
            Etapa::create(['nombre' => $etapa]);
        }
    }
}
