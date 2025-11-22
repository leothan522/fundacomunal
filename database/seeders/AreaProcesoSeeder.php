<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\AreaItem;
use App\Models\AreaProceso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaProcesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $procesosPorItem = [

            'PARTICIPACION' => [
                "ACOMPAÑAMIENTO_A_LAS_ASAMBLEAS_DE_CIUDADANOS_Y_CIUDADANAS" => [
                    "ASAMBLEA CONSTITUTIVA COMUNITARIA",
                    "ASAMBLEA INFORMATIVA DEL EQUIPO PROMOTOR PROVISIONAL",
                    "PARA LA ESCOGENCIA DE LA COMISIÓN ELECTORAL PERMANENTE.",
                    "PROCESO DE POSTULACIONES DE LAS VOCERÍAS DE CONSEJOS COMUNALES",
                    "PARA LA CONFORMACIÓN DEL CONSEJOS COMUNAL NUEVO",
                    "RENOVACIÓN DE VOCERÍAS.",
                    "JURAMENTACIÓN DE VOCERÍAS ELECTAS",
                    "PROCESO DE REVOCATORIO DE VOCERÍA"
                ],
                "ACOMPAÑAMIENTO_A_LA_ELABORACIÓN_DEL_PLAN" => [
                    "LA ELABORACIÓN DE LA AGENDA CONCRETA DE ACCIÓN (ACA)",
                    "ELABORACIÓN DEL PLAN COMUNITARIO DE DESARROLLO INTEGRAL",
                    "ELABORACIÓN MAPA DE PROBLEMA Y SOLUCIONES"
                ],
                "ACOMPAÑAMIENTO_A_COMUNAS" => [
                    "EN EL PROCESOS DE REFERÉNDUM DE CARTA FUNDACIONAL",
                    "EN LAS ELECCIONES DE LA COMISIÓN PROVISIONAL DE COMUNAS",
                    "ADECUACION DE COMUNAS",
                    "CONFORMACION DE COMUNAS",
                    "INSTANCIAS DE AUTOGOBIERNO"
                ],
                "ACOMPAÑAMINETO_A_LAS_UNIDADES" => [
                    "COLECTIVO DE GOBIERNO COMUNAL",
                    "UNIDAD EJECUTIVA (COMITES DE TRABAJO)",
                    "UNIDAD ADMINISTRATIVA Y FINANCIERA COMUNITARIA",
                    "UNIDAD DE CONTRALORIA SOCIAL",
                    "COMISION ELECTORAL"
                ],
                "ACOMPAÑAMIENTO_A_GABINETES_DE_GESTION_COMUNAL" => [
                    "REUNIÓN PREPARATORIA",
                    "INSTALACION DE GABINETE DE GESTIÓN COMUNAL",
                    "SEGUIMIENTO A LAS MESAS DE TRABAJO"
                ],
                "ACOMPAÑAMIENTOS_MUNICIPALES_ESTADALES" => [
                    "ACOMPAÑAMIENTO PROCESO MUNICIPALES ACA",
                    "ACOMPAÑAMIENTO PROCESO MUNICIPALES PARA EL REGISTRO DE CONSEJOS COMUNALES",
                    "ACOMPAÑAMIENTO PROCESOS MUNICIPALES ELECTORALES (TALLER CNE)",
                    "PLAN DE ATENCION A LAS VICTIMAS DE LA GUERRA ECONOMICA Y EL BLOQUEO/PLAN DE AMOR Y ACCION"
                ],
                "ACOMPAÑAMIENTO_EN_LA_ELABORACION_DE_LA_CARTOGRAFIA_COMUNAL" => [
                    "LEVANTAMIENTO CARTOGRAFICO",
                    "DIGITALIZACION DE MAPA",
                    "RESOLUCION DE CONFLICTO SOLAPAMIENTO",
                    "MESA DE TRABAJO PARA EL LEVANTAMIENTO DE COMUNAS"
                ],
                "DISCUSIÓN_DE_LEYES_DEL_PODER_POPULAR" => [
                    "NO APLICA"
                ],
                "PLANES_TEMPORADA_VACACIONALES" => [
                    "NO APLICA"
                ]
            ],
            'FORMACION' => [
                "PROCESOS_FORMATIVOS_LOCALES" => [
                    "3RNETS",
                    "COLECTIVO DE GOBIERNO COMUNAL",
                    "PLAN COMUNITARIO DE DESARROLLO INTEGRAL",
                    "SISTEMA ECONOMICO COMUNAL/CIRCUITOS ECONOMICOS",
                    "REGISTRO DE CONSEJOS COMUNALES",
                    "CARTOGRAFIA COMUNAL",
                    "COMISION ELECTORAL PERMANENTE",
                    "EQUIPO ELECTORAL PROVISIONAL",
                    "FUNCIONES DE VOCERIAS, COMITES Y MESAS TECNICAS",
                    "DEBATE DE LEYES",
                    "CONSEJO FEMINISTA",
                    "ELABORACION DE LA AGENDA CONCRETA DE ACCION (ACA)",
                    "CONGRESO DE EXPERIENCIAS EXITOSAS"
                ],
                "PROCESO_FORMATIVO_MUNICIPAL" => [
                    "3RNETS",
                    "COLECTIVO DE GOBIERNO COMUNAL",
                    "PLAN COMUNITARIO DE DESARROLLO INTEGRAL",
                    "SISTEMA ECONOMICO COMUNAL/CIRCUITOS ECONOMICOS",
                    "REGISTRO DE CONSEJOS COMUNALES",
                    "CARTOGRAFIA COMUNAL",
                    "COMISION ELECTORAL PERMANENTE",
                    "EQUIPO ELECTORAL PROVISIONAL",
                    "FUNCIONES DE VOCERIAS, COMITES Y MESAS TECNICAS",
                    "DEBATE DE LEYES",
                    "CONSEJO FEMINISTA",
                    "ELABORACION DE LA AGENDA CONCRETA DE ACCION (ACA)",
                    "CONGRESO DE EXPERIENCIAS EXITOSAS"
                ],
                "PROCESO_FORMATIVO_ESTADALES" => [
                    "3RNETS",
                    "COLECTIVO DE GOBIERNO COMUNAL",
                    "PLAN COMUNITARIO DE DESARROLLO INTEGRAL",
                    "SISTEMA ECONOMICO COMUNAL/CIRCUITOS ECONOMICOS",
                    "REGISTRO DE CONSEJOS COMUNALES",
                    "CARTOGRAFIA COMUNAL",
                    "COMISION ELECTORAL PERMANENTE",
                    "EQUIPO ELECTORAL PROVISIONAL",
                    "FUNCIONES DE VOCERIAS, COMITES Y MESAS TECNICAS",
                    "DEBATE DE LEYES",
                    "CONSEJO FEMINISTA",
                    "ELABORACION DE LA AGENDA CONCRETA DE ACCION (ACA)",
                    "ESCUELA VENEZOLANA DE PLANIFICACIÓN A NIVEL NACIONAL",
                    "CONGRESO DE EXPERIENCIAS EXITOSAS"
                ],
                "GOLPE_DE_TIMON_CULTURAL" => [
                    "CUMPLEAÑOS TRICOLOR",
                    "ESQUINAS CULTURALES",
                    "ENCUENTROS DE CRONISTAS"
                ]
            ],
            'FORTALECIMIENTO' => [
                "ACOMPAÑAMIENTO_LOCALES" => [
                    "ELABORACION DE PLANES PRODUCTIVOS",
                    "EN LAS MESAS DEL CONSEJO DE ECONOMIA",
                    "COMITE DE ECONOMIA COMUNAL PARA LA ACTIVACION DE (OSP)",
                    "PROMOCIÓN A LA BANCARIZACION"
                ],
                "ACOMPAÑAMIENTO_MUNICIPALES" => [
                    "ELABORACION DE PLANES PRODUCTIVOS",
                    "EN LAS MESAS DEL CONSEJO DE ECONOMIA",
                    "COMITE DE ECONOMIA COMUNAL PARA LA ACTIVACION DE (OSP)",
                    "PROMOCIÓN A LA BANCARIZACION"
                ],
                "ACOMPAÑAMIENTOS_ESTADALES" => [
                    "ELABORACION DE PLANES PRODUCTIVOS",
                    "EN LAS MESAS DEL CONSEJO DE ECONOMIA",
                    "COMITE DE ECONOMIA COMUNAL PARA LA ACTIVACION DE (OSP)",
                    "PROMOCIÓN A LA BANCARIZACION"
                ],
                "ACOMPAÑAMIENTO_AL_FUNCIONAMIENTO_O_REIMPULSO_DE_ORGANIZACIONES_SOCIOPRODUCTIVAS" => [
                    "EMPRESA DE PROPEDAD SOCIAL DIRECTA COMUNAL (EPSDC)",
                    "EMPRESA DE PROPIEDAD SOCIAL INDIRECTA COMUNAL (EPSIC)",
                    "UNIDAD PRODUCTIVA FAMILIAR (UPF)",
                    "GRUPO DE INTERCAMBIO SOLIDARIO",
                    "COOPERATIVAS"
                ],
                "ACOMPAÑAMIENTO_EN_EL_PROCESO_DE_ASAMBLEA_PARA_APROBACION_DE_PROYECTO" => [
                    "NO APLICA"
                ],
                "ACOMPAÑAMIENTO_A_LOS_PLANES_EN_DESARROLLO" => [
                    "TEXTIL",
                    "SIEMBRA",
                    "CONUCO Y CEREALES",
                    "VIVEROS COMUNALES(MINEC)",
                    "HUERTOS",
                    "PROYECTOS O PLANES DE OTRAS ENTIDADES"
                ],
                "CONSTRUCCION_DEL_CIRCUITO_ECONOMICO_ESTADAL" => [
                    "NO APLICA"
                ],
                "IDENTIFICACION_DE_LAS_EXPERIENCIAS_PRODUCTIVAS" => [
                    "EXPERIENCIAS PRODUCTIVAS EXITOSAS",
                    "CARACTERIZACION PLAN TURISMO COMUNAL"
                ],
                "ACOMPAÑAMIENTO_EN_ASAMBLEA_DE_RENDICION_DE_CUENTA" => [
                    "NO APLICA"
                ],
                "BIENES_NACIONALES" => [
                    "IDENTIFICACION",
                    "REVISION DE DOCUMENTO"
                ],
                "SINCO" => [
                    "CREACION DE USUARIO SINCO DE VOCEROS DEL CONSEJO COMUNAL"
                ]
            ]
        ];

        foreach ($procesosPorItem as $area => $items){
            $areas_id = Area::where('nombre', $area)->first()->id;
            if ($areas_id){
                foreach ($items as $item => $procesos){
                    $items_id = AreaItem::where('nombre', $item)->where('areas_id', $areas_id)->first()->id;
                    if ($items_id){
                        foreach ($procesos as $proceso){
                            AreaProceso::create([
                                'nombre' => $proceso,
                                'items_id' => $items_id
                            ]);
                        }
                    }
                }
            }
        }

    }
}
