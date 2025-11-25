<?php

namespace Database\Seeders;

use App\Models\GestionHumana;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CorregirGestionHumanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user){
            $gestionHumana = GestionHumana::where('email', $user->email)->first();
            if ($gestionHumana){
                $gestionHumana->users_id = $user->id;
                $gestionHumana->save();
            }
        }
    }
}
