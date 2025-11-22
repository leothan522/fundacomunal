<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            "ACTIVO",
            "REPOSO",
            "VACACIONES",
            "APOYO INSTITUCIONAL",
            "EN PROCESO DE JUBILACION",
            "NO REPORTÓ",
            "NO LABORA"
        ];

        foreach ($categorias as $categoria){
            Categoria::create([
               'nombre' => $categoria
            ]);
        }
    }
}
