<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ObppExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new ConsejosComunalesExport(),
            new CuadroGeneralExport(),
            new ComunasExport()
        ];
    }
}
