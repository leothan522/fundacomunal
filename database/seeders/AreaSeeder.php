<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areasSustantivas = [
            "PARTICIPACION",
            "FORMACION",
            "FORTALECIMIENTO"
        ];

        foreach ($areasSustantivas as $area){
            Area::create(['nombre' => $area]);
        }
    }
}
