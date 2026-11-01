<?php

namespace Database\Seeders;

use App\Models\BienCondicion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class BienCondicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $condiciones = ['Operativo', 'Regular', 'Inoperativo'];

        foreach ($condiciones as $condicion) {
            BienCondicion::firstOrCreate(['nombre' => $condicion]);
        }
        Role::create(['name' => 'BIENES']);
    }
}
