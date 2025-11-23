<?php

namespace Database\Seeders;

use App\Models\ModalidadFormacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModalidadFormacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modalidadesFormacion = [
            "PRESENCIAL",
            "VIRTUAL",
            "MIXTA"
        ];
        foreach ($modalidadesFormacion as $modalidad){
            ModalidadFormacion::create(['nombre' => $modalidad]);
        }
    }
}
