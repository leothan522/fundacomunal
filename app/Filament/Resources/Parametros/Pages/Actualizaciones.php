<?php

namespace App\Filament\Resources\Parametros\Pages;

class Actualizaciones
{
    public static function marzo2026(): void
    {
        $areasSustantivas = [
            "PARTICIPACION" => [
                "ACOMPAÑAMIENTO_A_LA_ELABORACION_DEL_PLAN",
                "ACOMPAÑAMIENTO_A_COMUNAS",
                "ACOMPAÑAMINETO_A_LOS_CONSEJOS_COMUNALES_Y__SUS_UNIDADES",
                "ACOMPAÑAMIENTO_A_LAS_SALAS_DE_AUTOGOBIERNO_COMUNAL",
                "ACOMPAÑAMIENTO_EN_LA_ELABORACION_DE_LA_CARTOGRAFIA_COMUNAL",
                "CONSULTA_POPULAR_NACIONAL",
                "DISCUSION_DE_LEYES_DEL_PODER_POPULAR",
                "PLANES_TEMPORADA_VACACIONALES"
            ],
            "FORMACION" => [
                "PROCESOS_FORMATIVOS_LOCALES",
                "PROCESO_FORMATIVO_MUNICIPAL",
                "PROCESO_FORMATIVO_ESTADALES"
            ],
            "FORTALECIMIENTO" => [
                "ELABORACION_DE_PLANES_PRODUCTIVOS",
                "SISTEMA_ECONOMICO_COMUNAL",
                "ACOMPAÑAMIENTO_AL_FUNCIONAMIENTO_O_REIMPULSO_DE_ORGANIZACIONES_SOCIOPRODUCTIVOS",
                "BANCOS_COMUNALES",
                "IDENTIFICACION_DE_LAS_EXPERIENCIAS_PRODUCTIVAS_ALTERNATIVAS",
                "ZONAS_ECONOMICAS_COMUNALES"
            ]
        ];

        $borrarAreaItems = \App\Models\AreaItem::get();
        foreach ($borrarAreaItems as $areaItem) {
            $areaItem->delete();
        }

        foreach ($areasSustantivas as $area => $items){
            $areas_id = \App\Models\Area::where('nombre', $area)->first()->id;
            if ($areas_id){
                foreach ($items as $item){
                    \App\Models\AreaItem::create([
                        'nombre' => $item,
                        'areas_id' => $areas_id
                    ]);
                }
            }
        }


        $procesosPorItem = [

            'PARTICIPACION' => [
                "ACOMPAÑAMIENTO_A_LA_ELABORACION_DEL_PLAN" => [
                    "LA ELABORACION/ACTUALIZACION DE LA AGENDA CONCRETA DE ACCION (ACA)",
                    "ELABORACION/ACTUALIZACION DEL PLAN COMUNITARIO DE DESARROLLO INTEGRAL",
                    "ELABORACION/ ACTUALIZACION MAPA DE PROBLEMA Y SOLUCIONES"
                ],
                "ACOMPAÑAMIENTO_A_COMUNAS" => [
                    "EN LAS ELECCION DE LA COMISION PROMOTORA DE LA COMUNA",
                    "REDACCION DEL PROYECTO DE LA CARTA FUNDACIONAL",
                    "INSTALACION DE LA COMISION ELECTORAL",
                    "REFERENDUM APROBATORIO DE CARTA FUNDACIONAL",
                    "ASAMBLEA INFORMATIVA PARA LA ADECUACION DE COMUNAS",
                    "ASAMBLEA DE PRODUCTORAS (ES) DE LAS OSP",
                    "SECION DE INSTALACION DE LAS INSTANCIAS DE GOBIERNO COMUNAL",
                    "ACOMPAÑMIENTO PARA EL REGISTRO DE  LA COMUNA",
                    "ACOMPAÑAMIENTO EN ASAMBLEA DE RENDICION DE CUENTA",
                ],
                "ACOMPAÑAMINETO_A_LOS_CONSEJOS_COMUNALES_Y__SUS_UNIDADES" => [
                    "COLECTIVO DE GOBIERNO COMUNAL",
                    "UNIDAD EJECUTIVA (COMITES DE TRABAJO)",
                    "UNIDAD ADMINISTRATIVA Y FINANCIERA COMUNITARIA",
                    "UNIDAD DE CONTRALORIA SOCIAL",
                    "COMISION ELECTORAL",
                    "ASAMBLEA INFORMATIVA DEL EQUIPO PROMOTOR PROVISIONAL",
                    "ASAMBLEA PARA LA ESCOGENCIA, RATIFICACION  DE LA COMISION ELECTORAL ",
                    "PROCESO DE POSTULACIONES DE LAS VOCERIAS DE CONSEJOS COMUNALES",
                    "JURAMENTACION DE VOCERIAS ELECTAS",
                    "PROCESO DE REVOCATORIO DE VOCERIA (ELECCION)",
                    "RENOVACION DE VOCERIAS. (ELECCION)",
                    "ASAMBLEA CONSTITUTIVA COMUNITARIA",
                    "ACOMPAÑMIENTO PARA EL REGISTRO DEL CONSEJO COMUNAL",
                    "ACOMPAÑAMIENTO EN ASAMBLEA DE RENDICION DE CUENTA"
                ],
                "ACOMPAÑAMIENTO_A_LAS_SALAS_DE_AUTOGOBIERNO_COMUNAL" => [
                    "REUNION DE MESAS DE TRABAJO 7T",
                    "SEGUIMIENTO A LAS MESAS DE TRABAJO",
                ],
                "ACOMPAÑAMIENTO_EN_LA_ELABORACION_DE_LA_CARTOGRAFIA_COMUNAL" => [
                    "LEVANTAMIENTO CARTOGRAFICO",
                    "DIGITALIZACION DE MAPA",
                    "RESOLUCION DE CONFLICTO SOLAPAMIENTO",
                    "MESA DE TRABAJO PARA EL LEVANTAMIENTO DE COMUNAS",
                    "CARACTERIZACION DE LA CARTOGRAFIA SOCIAL"
                ],
                "CONSULTA_POPULAR_NACIONAL" => [
                    "ASAMBLEAS PARA LA ESCOGENCIA DE LA COMISION ELECTORAL ",
                    "POSTULACION DE PROYECTOS ",
                    "VINCULACION DE CENTROS Y MESAS ELECTORALES",
                    "POSTULACION DE MIEMBROS DE MESA",
                    "CAPACITACION Y FORMACION DE LOS MIEMBROS DE MESA",
                    "ACOMPAÑAMIENTO A EL PROCESO DE LA CONSULTA",
                    "ACOMPAÑAMIENTO EN ASAMBLEA DE RENDICION DE CUENTA"
                ],
                "DISCUSION_DE_LEYES_DEL_PODER_POPULAR" => [
                    'NO APLICA'
                ],
                "PLANES_TEMPORADA_VACACIONALES" => [
                    "NO APLICA"
                ]
            ],
            'FORMACION' => [
                "PROCESOS_FORMATIVOS_LOCALES" => [
                    "7 TRANSFORMACIONES( 7T)",
                    "COLECTIVO DE GOBIERNO COMUNAL",
                    "PLAN COMUNITARIO DE DESARROLLO INTEGRAL",
                    "SISTEMA ECONOMICO COMUNAL/CIRCUITOS ECONOMICOS",
                    "REGISTRO DE CONSEJOS COMUNALES",
                    "CARTOGRAFIA COMUNAL",
                    "COMISION ELECTORAL ",
                    "COMISION PROMOTORA",
                    "FUNCIONES DE VOCERIAS, COMITES Y MESAS TECNICAS",
                    "DEBATE DE LEYES",
                    "ELABORACION DE LA AGENDA CONCRETA DE ACCION (ACA)",
                    "FUNCIONES DE LAS INSTANCIAS DE LA COMUNA"
                ],
                "PROCESO_FORMATIVO_MUNICIPAL" => [
                    "7 TRANSFORMACIONES( 7T)",
                    "COLECTIVO DE GOBIERNO COMUNAL",
                    "PLAN COMUNITARIO DE DESARROLLO INTEGRAL",
                    "SISTEMA ECONOMICO COMUNAL/CIRCUITOS ECONOMICOS",
                    "REGISTRO DE CONSEJOS COMUNALES",
                    "CARTOGRAFIA COMUNAL",
                    "COMISION ELECTORAL PERMANENTE",
                    "EQUIPO ELECTORAL PROVISIONAL",
                    "FUNCIONES DE VOCERIAS, COMITES Y MESAS TECNICAS",
                    "DEBATE DE LEYES",
                    "ELABORACION DE LA AGENDA CONCRETA DE ACCION (ACA)",
                    "FUNCIONES DE LAS INSTANCIAS DE LA COMUNA"
                ],
                "PROCESO_FORMATIVO_ESTADALES" => [
                    "7 TRANSFORMACIONES( 7T)",
                    "COLECTIVO DE GOBIERNO COMUNAL",
                    "PLAN COMUNITARIO DE DESARROLLO INTEGRAL",
                    "SISTEMA ECONOMICO COMUNAL/CIRCUITOS ECONOMICOS",
                    "REGISTRO DE CONSEJOS COMUNALES",
                    "CARTOGRAFIA COMUNAL",
                    "COMISION ELECTORAL PERMANENTE",
                    "EQUIPO ELECTORAL PROVISIONAL",
                    "FUNCIONES DE VOCERIAS, COMITES Y MESAS TECNICAS",
                    "DEBATE DE LEYES",
                    "ELABORACION DE LA AGENDA CONCRETA DE ACCION (ACA)",
                    "FUNCIONES DE LAS INSTANCIAS DE LA COMUNA"
                ]
            ],
            'FORTALECIMIENTO' => [
                "ELABORACION_DE_PLANES_PRODUCTIVOS" => [
                    "IMPULSAR Y ACOMPAÑAR LA CREACION DE PROPUESTAS SOCIO PRODUCTIVAS EN LAS AGENDAS DE ACCION CONCRETA DE LAS COMUNAS",
                    "FOMENTO DE NUEVOS PLANES DE DESARROLLO SOCIO PRODUTIVOS"
                ],
                "SISTEMA_ECONOMICO_COMUNAL" => [
                    "ACOMPAÑAMIENTO AL COMITÉ DE ECONOMIA COMUNAL",
                    "ACOMPAÑAMIENTO AL CONSEJO DE ECONOMIA COMUNAL"
                ],
                "ACOMPAÑAMIENTO_AL_FUNCIONAMIENTO_O_REIMPULSO_DE_ORGANIZACIONES_SOCIOPRODUCTIVOS" => [
                    "EMPRESA DE PROPIEDAD SOCIAL DIRECTA COMUNAL (EPSDC)",
                    "EMPRESA DE PROPIEDAD SOCIAL INDIRESTA COMUNAL (EPSDC)",
                    "UNIDAD PRODUCTIVA FAMILIAR (UPF)",
                    "GRUPO DE INTERCAMBIO SOCIALISTA",
                    "COOPERATIVAS"
                ],
                "BANCOS_COMUNALES" => [
                    "SEGUIMIENTO DE LAS FUNCIONES DE LOS BANCOS COMUNALES",
                    "CONFORMACION DE LOS BANCO COMUNALES"
                ],
                "IDENTIFICACION_DE_LAS_EXPERIENCIAS_PRODUCTIVAS_ALTERNATIVAS" => [
                    "EXPERIENCIAS PRODUCTIVAS EXITOSA",
                    "CARACTERIZACION PLAN TURISMO COMUNAL",
                ],
                "ZONAS_ECONOMICAS_COMUNALES" => [
                    "DIAGNOSTICO DE LAS ZONAS PRODUCTIVAS",
                    "CICLOS PRODUCTIVOS COMUNALES"
                ]
            ]
        ];

        $borrarAreaProcesos = \App\Models\AreaProceso::get();
        foreach ($borrarAreaProcesos as $proceso) {
            $proceso->delete();
        }

        foreach ($procesosPorItem as $area => $items){
            $areas_id = \App\Models\Area::where('nombre', $area)->first()->id;
            if ($areas_id){
                foreach ($items as $item => $procesos){
                    $items_id = \App\Models\AreaItem::where('nombre', $item)->where('areas_id', $areas_id)->first()->id;
                    if ($items_id){
                        foreach ($procesos as $proceso){
                            \App\Models\AreaProceso::create([
                                'nombre' => $proceso,
                                'items_id' => $items_id
                            ]);
                        }
                    }
                }
            }
        }

        \App\Models\Parametro::create([
            'nombre' => 'marzo_2026',
            'valor_id' => 1,
            'valor_texto' => 'Actualización Nacional Procesos',
        ]);

    }

}
