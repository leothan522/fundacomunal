<?php

namespace Database\Seeders;

use App\Models\TipoPersonal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoPersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoPersonal = [
            "ADMINISTRATIVO",
            "OBRERO",
            "PROMOTORES"
        ];

        foreach ($tipoPersonal as $tipo){
            TipoPersonal::create(['nombre' => $tipo]);
        }
    }
}
