<?php

namespace App\Imports;

use App\Models\Comuna;
use App\Models\ConsejoComunal;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Redi;
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
            $parroquia = $row[2];
            $tipo = $row[3];
            $codigoComuna = $row[4];
            $siturViejo = $row[7];
            $siturNuevo = $row[8];
            $nombre = $row[9];
            $fecha_asamblea = $row[10];
            $fecha_vencimiento = $row[11];

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

            ConsejoComunal::create([
                'nombre' => $nombre,
                'situr_viejo' => $siturViejo,
                'situr_nuevo' => $siturNuevo,
                'tipo' => $tipo,
                'fecha_asamblea' => $fecha_asamblea,
                'fecha_vencimiento' => $fecha_vencimiento,
                'comunas_id' => Comuna::where('cod_com', $codigoComuna)->first()?->id,
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
