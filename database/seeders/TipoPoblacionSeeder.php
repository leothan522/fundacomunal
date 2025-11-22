<?php

namespace Database\Seeders;

use App\Models\TipoPoblacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoPoblacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposPoblacion = [
            "RURAL",
            "URBANO",
            "MIXTO",
            "INDIGENA"
        ];

        foreach ($tiposPoblacion as $tipo){
            TipoPoblacion::create(['nombre' => $tipo]);
        }
    }
}
