<?php

namespace Database\Seeders;

use App\Models\Redi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RediSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $redis = [
            'CAPITAL', 'OCCIDENTAL', 'ANDES', 'CENTRAL',
            'LLANOS', 'GUAYANA', 'ORIENTAL', 'INSULAR'
        ];

        foreach ($redis as $redi){
            Redi::create(['nombre' => $redi]);
        }

    }
}
