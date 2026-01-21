<?php

namespace App\Imports;

use App\Models\Comuna;
use App\Models\ConsejoComunal;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Redi;
use App\Models\TipoPoblacion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ConsejoComunalImport implements ToCollection, WithStartRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {

            $municipio = $row[0];
            $parroquia = $row[1];
            $tipoCC = $row[2];
            $codigoCOM = $row[3];
            $siturNuevaComuna = $row[6];
            $siturViejo = $row[8];
            $siturNuevo = $row[9];
            $nombre = $row[10];
            $fecha_asamblea = $row[11];
            $fecha_vencimiento = $row[12];

            $estados_id = Estado::where('nombre', 'GUARICO')->first()?->id;

            $fecha1 = null;
            $fecha2 = null;
            if (is_numeric($fecha_asamblea)){
                $fecha1 = Date::excelToDateTimeObject($fecha_asamblea)->format('Y-m-d');
            }
            if (is_numeric($fecha_vencimiento)){
                $fecha2 = Date::excelToDateTimeObject($fecha_vencimiento)->format('Y-m-d');
            }
            $fecha_asamblea = $fecha1;
            $fecha_vencimiento = $fecha2;

            $tipos_id = null;
            $tipoPoblacion = TipoPoblacion::where('nombre', $tipoCC)->first();
            $tipos_id = $tipoPoblacion?->id;

            if (empty($siturNuevaComuna)){
                $comunas_id = Comuna::where('cod_com', $codigoCOM)->first()?->id;
            }else{
                $comunas_id = Comuna::where('cod_situr', $siturNuevaComuna)->first()?->id;
            }

            ConsejoComunal::create([
                'nombre' => $nombre,
                'situr_viejo' => $siturViejo,
                'situr_nuevo' => $siturNuevo,
                'tipos_poblacion_id' => $tipos_id,
                'fecha_asamblea' => $fecha_asamblea,
                'fecha_vencimiento' => $fecha_vencimiento,
                'comunas_id' => $comunas_id,
                'redis_id' => Redi::where('nombre', 'LLANOS')->first()?->id,
                'estados_id' => $estados_id,
                'municipios_id' => Municipio::where('estados_id', $estados_id)->where('nombre', $municipio)->first()?->id,
                'parroquia' => $parroquia
            ]);


        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
