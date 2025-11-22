<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Models\Redi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $estadosPorRedi = [
            'CAPITAL' => ['DISTRITO CAPITAL'],
            'OCCIDENTAL' => ['FALCÓN', 'MIRANDA'],
            'ANDES' => ['MÉRIDA', 'LARA', 'LA GUAIRA', 'ZULIA'],
            'CENTRAL' => ['ARAGUA', 'TÁCHIRA', 'CARABOBO', 'TRUJILLO', 'YARACUY'],
            'LLANOS' => ['APURE', 'GUÁRICO', 'BARINAS', 'COJEDES', 'DELTA AMACURO', 'PORTUGUESA'],
            'GUAYANA' => ['AMAZONAS', 'BOLÍVAR'],
            'ORIENTAL' => ['ANZOÁTEGUI', 'MONAGAS', 'SUCRE'],
            'INSULAR' => ['NUEVA ESPARTA'],
        ];

        foreach ($estadosPorRedi as $redi => $estados){
            $redis_id = Redi::where('nombre', $redi)->first()->id;
            if ($redis_id){
                foreach ($estados as $estado){
                    Estado::create([
                        'nombre' => $estado,
                        'redis_id' => $redis_id
                    ]);
                }
            }
        }

    }
}
