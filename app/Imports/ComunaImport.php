<?php

namespace App\Imports;

use App\Models\Comuna;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Redi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ComunaImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection): void
    {
        foreach ($collection as $row){
            $municipio = $row[0];
            $parroquia = $row[1];
            $codCOM = $row[2];
            $codSITUR = $row[3];
            $nombre = $row[4];
            $cantidadCC = intval($row[5]);

            if (is_numeric($codSITUR)){
                $fecha = Date::excelToDateTimeObject($codSITUR)->format('d-m-Y');
                $codSITUR = $fecha;
            }

            $estados_id = Estado::where('nombre', 'GUARICO')->first()?->id;

            Comuna::create([
                'nombre' => $nombre,
                'cod_com' => $codCOM,
                'cod_situr' => $codSITUR,
                'cantidad_cc' => $cantidadCC,
                'redis_id' => Redi::where('nombre', 'LLANOS')->first()?->id,
                'estados_id' => $estados_id,
                'municipios_id' => Municipio::where('estados_id', $estados_id)->where('nombre_cne', $municipio)->first()?->id,
                'parroquia' => $parroquia
            ]);

        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
