<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::has('trabajador')->get();
        foreach ($users as $user) {
            if ($user->trabajador->tipoPersonal->nombre == 'PROMOTORES'){
                $user->assignRole('FORMACION');
            }
        }
    }
}
