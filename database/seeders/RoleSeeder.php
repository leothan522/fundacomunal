<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Rol Admin
        Role::create([
            'name' => 'admin',
        ]);

        $areasSustantivas = [
            "PARTICIPACION",
            "FORMACION",
            "FORTALECIMIENTO",
            'GESTION HUMANA',
        ];
        foreach ($areasSustantivas as $area){
            Role::create(['name' => $area]);
        }
    }
}
