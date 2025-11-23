<?php

namespace Database\Seeders;

use App\Models\GestionHumana;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Usuario Root
        $root = User::factory()->create([
            'name' => config('app.root_name') ? config('app.root_name') : 'Administrador',
            'email' => config('app.root_email') ? config('app.root_email') : 'admin@morros-devops.xyz',
            'email_verified_at' => now(),
            'password' => Hash::make(config('app.root_password') ? config('app.root_password') : 'admin1234'),
            'is_root' => 1,
        ]);

        //Usuario Administrador
        if ($root->email != 'admin@morros-devops.xyz') {
            $admin = User::factory()->create([
                'name' => 'Administrador',
                'email' => 'admin@morros-devops.xyz',
                'email_verified_at' => now(),
                'password' => Hash::make('admin1234'),
            ]);
            $admin->assignRole('admin');
        }

        //Usuarios Para el Personal
        $gestiosHumana = GestionHumana::all();
        foreach ($gestiosHumana as $nomina){
            $exite = User::where('email', $nomina->email)->first();
            if (!$exite && !empty($nomina->email)){

                $user = User::factory()->create([
                  'name' => $nomina->nombre.' '.$nomina->apellido,
                  'email' => $nomina->email,
                  'password' => Hash::make($nomina->cedula),
                  'phone' => $nomina->telefono,
                  'access_panel' => 1,
                ]);

                if ($nomina->tipoPersonal->nombre == 'PROMOTORES'){
                    $user->assignRole('PARTICIPACION');
                }

                if ($nomina->tipoPersonal->nombre == 'COORDINADOR(A) ESTADAL'){
                    $user->assignRole('admin');
                }

                //JOSE HERNADEZ - CONTROL Y SEGUIMIENTO
                if ($nomina->cedula == '12840320'){
                    $user->assignRole('admin');
                }

                //ALIDA ARREAZA - RRHH
                if ($nomina->cedula == '9892363'){
                    $user->assignRole('GESTION HUMANA');
                }

                //ZULIMAR RODRIGUEZ - FORMACION
                if ($nomina->cedula == '18803525'){
                    $user->assignRole('FORMACION');
                }

            }
        }
    }
}
