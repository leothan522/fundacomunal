<?php

namespace Database\Seeders;

use App\Models\MedioVerificacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedioVerificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mediosVerificacion = [
            "LISTA DE ASISTENCIA",
            "FOTOGRAFIA",
            "AMBOS"
        ];
        foreach ($mediosVerificacion as $medio){
            MedioVerificacion::create(['nombre' => $medio]);
        }
    }
}
