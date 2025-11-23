<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        $this->call([
            RediSeeder::class,
            EstadoSeeder::class,
            MunicipioSeeder::class,
            TipoPersonalSeeder::class,
            CategoriaSeeder::class,
            TipoObppSeeder::class,
            TipoPoblacionSeeder::class,
            AreaSeeder::class,
            AreaItemSeeder::class,
            AreaProcesoSeeder::class,
            TipoEconomicaSeeder::class,
            EtapaSeeder::class,
            EstrategiaFormacionSeeder::class,
            ModalidadFormacionSeeder::class,
            MedioVerificacionSeeder::class,
            GestionHumanaSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);

    }
}
