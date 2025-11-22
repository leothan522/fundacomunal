<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\AreaItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areasSustantivas = [
            "PARTICIPACION" => [
                "ACOMPAÑAMIENTO_A_LAS_ASAMBLEAS_DE_CIUDADANOS_Y_CIUDADANAS",
                "ACOMPAÑAMIENTO_A_LA_ELABORACIÓN_DEL_PLAN",
                "ACOMPAÑAMIENTO_A_COMUNAS",
                "ACOMPAÑAMINETO_A_LAS_UNIDADES",
                "ACOMPAÑAMIENTO_A_GABINETES_DE_GESTION_COMUNAL",
                "ACOMPAÑAMIENTOS_MUNICIPALES_ESTADALES",
                "ACOMPAÑAMIENTO_EN_LA_ELABORACION_DE_LA_CARTOGRAFIA_COMUNAL",
                "DISCUSIÓN_DE_LEYES_DEL_PODER_POPULAR",
                "PLANES_TEMPORADA_VACACIONALES"
            ],
            "FORMACION" => [
                "PROCESOS_FORMATIVOS_LOCALES",
                "PROCESO_FORMATIVO_MUNICIPAL",
                "PROCESO_FORMATIVO_ESTADALES",
                "GOLPE_DE_TIMON_CULTURAL"
            ],
            "FORTALECIMIENTO" => [
                "ACOMPAÑAMIENTO_LOCALES",
                "ACOMPAÑAMIENTO_MUNICIPALES",
                "ACOMPAÑAMIENTOS_ESTADALES",
                "ACOMPAÑAMIENTO_AL_FUNCIONAMIENTO_O_REIMPULSO_DE_ORGANIZACIONES_SOCIOPRODUCTIVAS",
                "ACOMPAÑAMIENTO_EN_EL_PROCESO_DE_ASAMBLEA_PARA_APROBACION_DE_PROYECTO",
                "ACOMPAÑAMIENTO_A_LOS_PLANES_EN_DESARROLLO",
                "CONSTRUCCION_DEL_CIRCUITO_ECONOMICO_ESTADAL",
                "IDENTIFICACION_DE_LAS_EXPERIENCIAS_PRODUCTIVAS",
                "ACOMPAÑAMIENTO_EN_ASAMBLEA_DE_RENDICION_DE_CUENTA",
                "BIENES_NACIONALES",
                "SINCO"
            ]
        ];

        foreach ($areasSustantivas as $area => $items){
            $areas_id = Area::where('nombre', $area)->first()->id;
            if ($areas_id){
                foreach ($items as $item){
                    AreaItem::create([
                        'nombre' => $item,
                        'areas_id' => $areas_id
                    ]);
                }
            }
        }


    }
}
